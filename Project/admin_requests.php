<?php
require_once 'config.php';
require_once 'utils.php';
require_once 'db.php';

session_start();

if (!isAuthenticated()) {
    redirect('login.php');
}

$user = getCurrentUser();

if (!in_array($user['role'], ['admin', 'staff'])) {
    http_response_code(403);
    die("<h1>Access Denied</h1>");
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $req_id = intval($_POST['request_id'] ?? 0);
    $status = trim($_POST['status'] ?? '');
    $remarks = sanitizeInput($_POST['remarks'] ?? '');

    if ($req_id > 0 && in_array($status, ['pending', 'approved', 'rejected', 'completed'])) {
        try {
            $sql = "UPDATE certificate_requests SET status = ?, remarks = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$status, $remarks, $req_id]);
            $message = 'Updated successfully!';
        } catch (PDOException $e) {
            $message = 'Error updating.';
            logError("Update: " . $e->getMessage());
        }
    }
}

try {
    $sql = "SELECT r.*, u.fullname, u.email FROM certificate_requests r JOIN users u ON r.user_id = u.id ORDER BY r.created_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $requests = $stmt->fetchAll();
} catch (PDOException $e) {
    logError("Error: " . $e->getMessage());
    $requests = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Requests - <?php echo APP_NAME; ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, sans-serif; background: #f8f9fa; padding: 20px; }
        .nav { background: linear-gradient(135deg, #2c5a2d 0%, #1e4e22 100%); padding: 16px 24px; color: white; display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.12); }
        .nav h2 { margin: 0; font-size: 20px; font-weight: 600; }
        .nav a { color: rgba(255,255,255,0.95); text-decoration: none; margin: 0 12px; font-size: 14px; transition: color 0.3s ease; }
        .nav a:hover { color: #fff; }
        .container { max-width: 1200px; margin: 0 auto; }
        h1 { color: #1e4e22; text-align: center; margin-bottom: 24px; font-size: 26px; font-weight: 600; }
        .message { background: #efe; color: #263; padding: 14px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #cec; }
        table { width: 100%; background: white; border-collapse: collapse; box-shadow: 0 2px 8px rgba(0,0,0,0.08); border-radius: 12px; overflow: hidden; border: 1px solid rgba(0,0,0,0.05); }
        th { background: linear-gradient(135deg, #2c5a2d 0%, #1e4e22 100%); color: white; padding: 16px; text-align: left; font-weight: 600; font-size: 14px; }
        td { padding: 14px 16px; border-bottom: 1px solid #e9ecef; font-size: 14px; }
        tr:hover { background: #fafbfc; }
        .badge { display: inline-block; padding: 6px 12px; border-radius: 20px; color: white; font-size: 11px; font-weight: 600; }
        .pending { background: #ffc107; color: #333; }
        .approved { background: #28a745; }
        .completed { background: #17a2b8; }
        .rejected { background: #dc3545; }
        button { padding: 8px 16px; background: linear-gradient(135deg, #3e8f15 0%, #2d6610 100%); color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 600; transition: all 0.3s ease; }
        button:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(62,143,21,0.3); }
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 1000; justify-content: center; align-items: center; padding: 20px; }
        .modal.show { display: flex; }
        .modal-content { background: white; padding: 32px; border-radius: 12px; max-width: 500px; width: 100%; box-shadow: 0 20px 60px rgba(0,0,0,0.3); }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
        .modal-header h2 { margin: 0; color: #1e4e22; font-size: 20px; font-weight: 600; }
        .close { background: none; border: none; font-size: 28px; cursor: pointer; color: #999; padding: 0; transition: color 0.3s ease; }
        .close:hover { color: #333; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #1e4e22; font-size: 14px; }
        .form-group select, .form-group textarea { width: 100%; padding: 12px 14px; border: 1.5px solid #d0d0d0; border-radius: 8px; font-family: inherit; font-size: 14px; transition: all 0.3s ease; }
        .form-group select:focus, .form-group textarea:focus { outline: none; border-color: #3e8f15; box-shadow: 0 0 0 3px rgba(62,143,21,0.1); }
        .modal-actions { display: flex; gap: 12px; justify-content: flex-end; margin-top: 24px; }
        .btn-submit { background: linear-gradient(135deg, #3e8f15 0%, #2d6610 100%); }
        .btn-cancel { background: #e9ecef; color: #333; }
        .btn-cancel:hover { background: #dee2e6; }
        .empty { text-align: center; padding: 60px 20px; background: white; border-radius: 12px; border: 1px solid rgba(0,0,0,0.05); }
        .empty h2 { color: #1e4e22; margin-bottom: 8px; }
        .empty p { color: #666; }
    </style>
</head>
<body>
    <div class="nav">
        <h2><?php echo APP_NAME; ?></h2>
        <div>
            <a href="dashboard.php">Dashboard</a>
            <a href="admin_bookings.php">Manage Bookings</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="container">
        <h1>Manage Certificate Requests</h1>

        <?php if ($message): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <?php if (empty($requests)): ?>
            <div class="empty">
                <h2>No Requests</h2>
                <p>No certificate requests to review.</p>
            </div>
        <?php else: ?>
            <table>
                <tr>
                    <th>Resident</th>
                    <th>Certificate Type</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($requests as $r): ?>
                <tr>
                    <td>
                        <strong><?php echo htmlspecialchars($r['fullname']); ?></strong><br>
                        <small><?php echo htmlspecialchars($r['email']); ?></small>
                    </td>
                    <td><?php echo htmlspecialchars($r['certificate_type']); ?></td>
                    <td><?php echo date('M d, Y', strtotime($r['created_at'])); ?></td>
                    <td><span class="badge <?php echo htmlspecialchars($r['status']); ?>"><?php echo ucfirst($r['status']); ?></span></td>
                    <td><button onclick="openModal(<?php echo $r['id']; ?>, '<?php echo htmlspecialchars($r['status']); ?>')">Review</button></td>
                </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>

    <div id="modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Update Status</h2>
                <button class="close" onclick="closeModal()">×</button>
            </div>
            <form method="POST">
                <input type="hidden" name="action" value="update_status">
                <input type="hidden" name="request_id" id="reqId">

                <div class="form-group">
                    <label>Status</label>
                    <select id="status" name="status">
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="completed">Completed</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Remarks</label>
                    <textarea id="remarks" name="remarks" style="min-height: 80px;"></textarea>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="closeModal()">Cancel</button>
                    <button type="submit" class="btn-submit">Update</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(id, status) {
            document.getElementById('reqId').value = id;
            document.getElementById('status').value = status;
            document.getElementById('remarks').value = '';
            document.getElementById('modal').classList.add('show');
        }
        function closeModal() {
            document.getElementById('modal').classList.remove('show');
        }
        window.onclick = function(e) {
            const modal = document.getElementById('modal');
            if (e.target == modal) closeModal();
        }
    </script>
</body>
</html>

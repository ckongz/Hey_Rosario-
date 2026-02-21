<?php
/**
 * HASH PASSWORDS — Run ONCE after importing database-schema.sql
 * Visit: http://localhost/barangay_rosario/hash_passwords.php
 * Then DELETE this file.
 */
require_once 'includes/db-connection.php';

if (!$pdo) { die('<h2 style="color:red">❌ Database connection failed. Check db-connection.php</h2>'); }

$results = [];

// Hash admin passwords
try {
    $admins = $pdo->query("SELECT admin_id, email, password FROM admins")->fetchAll();
    foreach ($admins as $a) {
        if (strlen($a['password']) < 50) {
            $hashed = password_hash($a['password'], PASSWORD_BCRYPT, ['cost'=>12]);
            $pdo->prepare("UPDATE admins SET password=? WHERE admin_id=?")->execute([$hashed, $a['admin_id']]);
            $results[] = ['✅','Admin',$a['email'],'Password hashed'];
        } else {
            $results[] = ['⏭️','Admin',$a['email'],'Already hashed'];
        }
    }
} catch(PDOException $e) { $results[] = ['❌','Admin','ALL','Error: '.$e->getMessage()]; }

// Hash user passwords
try {
    $users = $pdo->query("SELECT user_id, email, password FROM users")->fetchAll();
    foreach ($users as $u) {
        if (strlen($u['password']) < 50) {
            $hashed = password_hash($u['password'], PASSWORD_BCRYPT, ['cost'=>12]);
            $pdo->prepare("UPDATE users SET password=? WHERE user_id=?")->execute([$hashed, $u['user_id']]);
            $results[] = ['✅','User',$u['email'],'Password hashed'];
        } else {
            $results[] = ['⏭️','User',$u['email'],'Already hashed'];
        }
    }
} catch(PDOException $e) { $results[] = ['❌','User','ALL','Error: '.$e->getMessage()]; }

?><!DOCTYPE html>
<html><head><title>Hash Passwords — Hey Rosario!</title>
<style>body{font-family:sans-serif;max-width:700px;margin:50px auto;padding:20px;}table{width:100%;border-collapse:collapse;}th,td{padding:10px;border:1px solid #ddd;text-align:left;}th{background:#69000E;color:white;}tr:nth-child(even){background:#fff8e7;}.info{background:#e7f5ff;padding:15px;border-radius:8px;margin-top:20px;}</style>
</head><body>
<h2>🔐 Hey Rosario! — Password Hashing</h2>
<table><thead><tr><th>Status</th><th>Type</th><th>Email</th><th>Result</th></tr></thead><tbody>
<?php foreach($results as $r): ?><tr><td><?php echo $r[0]; ?></td><td><?php echo $r[1]; ?></td><td><?php echo htmlspecialchars($r[2]); ?></td><td><?php echo htmlspecialchars($r[3]); ?></td></tr><?php endforeach; ?>
</tbody></table>
<div class="info">
    <strong>✅ Done!</strong> All passwords have been hashed. Test accounts:<br><br>
    <strong>Admins:</strong> admin@heyrosario.com / Admin123! &nbsp;|&nbsp; captain@heyrosario.com / Captain123!<br>
    <strong>Citizens:</strong> citizen1@email.com / Citizen123! &nbsp;|&nbsp; citizen2@email.com / Citizen123!<br>
    <strong>Guest:</strong> guest1@email.com / Guest123!<br>
    <strong>Pending:</strong> pendinguser@email.com / Pending123!<br><br>
    <span style="color:red;"><strong>⚠️ IMPORTANT: Delete this file after running it!</strong><br><code>rm /path/to/barangay_rosario/hash_passwords.php</code></span>
</div>
</body></html>

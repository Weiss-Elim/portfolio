<?php
session_start();
require_once __DIR__ . '/../config/database.php';

// Connect to DB
$db = null;
try {
    $db = getDBConnection();
} catch (Exception $e) {
    exit("Database Connection Error: " . $e->getMessage());
}

// ----------------------------------------------------
// 1. AUTHENTICATION LOGIC
// ----------------------------------------------------
$auth_error = '';

// Logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header('Location: index.php');
    exit;
}

// 1. Fixed Login POST Handler in admin/index.php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login_submit'])) {
    $user_input = trim($_POST['username'] ?? '');
    $pass_input = $_POST['password'] ?? '';

    if (!empty($user_input) && !empty($pass_input)) {
        // Use distinct parameter names for username and email
        $stmt = $db->prepare("SELECT * FROM users WHERE username = :username OR email = :email LIMIT 1");
        
        // Pass both parameters in the array
        $stmt->execute([
            ':username' => $user_input,
            ':email'    => $user_input
        ]);
        
        $user = $stmt->fetch();

        if ($user && password_verify($pass_input, $user['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_user'] = $user['username'];
            header('Location: index.php');
            exit;
        } else {
            $auth_error = 'Invalid username or password.';
        }
    } else {
        $auth_error = 'Please fill in all fields.';
    }
}

// If not logged in, show Login Screen
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true):
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Portfolio Admin Login</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <style>
    body { background-color: #0B0F14; display: flex; align-items: center; justify-content: center; min-height: 100vh; font-family: 'Space Mono', monospace; }
    .login-card { width: 100%; max-width: 400px; padding: 2rem; background: #151B23; border: 1px solid #263241; border-radius: 8px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); }
    .login-card h2 { color: #3B82F6; margin-bottom: 1.5rem; text-align: center; }
    .form-group { margin-bottom: 1rem; }
    .form-group label { display: block; color: #94A3B8; font-size: 0.8rem; margin-bottom: 0.4rem; }
    .form-group input { width: 100%; padding: 0.6rem; background: #0B0F14; border: 1px solid #263241; color: #F8FAFC; border-radius: 4px; font-family: inherit; }
    .btn-login { width: 100%; padding: 0.75rem; background: #3B82F6; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-family: inherit; font-weight: bold; }
    .btn-login:hover { background: #2563EB; }
    .error-msg { color: #EF4444; font-size: 0.8rem; margin-bottom: 1rem; text-align: center; }
  </style>
</head>
<body>
  <div class="login-card">
    <h2>🔐 ADMIN ACCESS</h2>
    <?php if ($auth_error): ?>
      <div class="error-msg"><?php echo htmlspecialchars($auth_error); ?></div>
    <?php endif; ?>
    <form method="POST" action="index.php">
      <div class="form-group">
        <label>Username / Email</label>
        <input type="text" name="username" required autocomplete="off">
      </div>
      <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" required>
      </div>
      <button type="submit" name="login_submit" class="btn-login">SYSTEM LOGIN</button>
    </form>
  </div>
</body>
</html>
<?php 
exit;
endif;

// ----------------------------------------------------
// 2. DASHBOARD POST & CRUD HANDLERS
// ----------------------------------------------------
$message = '';
$active_tab = $_GET['tab'] ?? 'projects';

// Item Deletion
if (isset($_GET['delete_table']) && isset($_GET['delete_id'])) {
    $table = $_GET['delete_table'];
    $id = (int)$_GET['delete_id'];
    $allowed_tables = ['projects', 'skills', 'experience', 'education', 'certifications', 'messages'];
    
    if (in_array($table, $allowed_tables)) {
        $stmt = $db->prepare("DELETE FROM `$table` WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $message = "Record deleted successfully from " . htmlspecialchars($table) . ".";
    }
}

// Insert Handlers
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ADD PROJECT
    if (isset($_POST['add_project'])) {
        $title = trim($_POST['title']);
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
        $category = $_POST['category'];
        $summary = trim($_POST['summary']);
        $description = trim($_POST['description']);
        $tech = trim($_POST['technologies']);
        $github = trim($_POST['github_url']);
        $demo = trim($_POST['demo_url']);
        
        $thumbnail = 'default-project.jpg';
        if (!empty($_FILES['thumbnail']['name']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
            $targetDir = __DIR__ . '/../assets/images/projects/';
            
            // Create target directory if it doesn't exist
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            $ext = pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION);
            $thumbnail = time() . '_proj.' . $ext;
            move_uploaded_file($_FILES['thumbnail']['tmp_name'], $targetDir . $thumbnail);
        }

        $stmt = $db->prepare("INSERT INTO projects (title, slug, category, summary, description, thumbnail, technologies, github_url, demo_url, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'published')");
        $stmt->execute([$title, $slug, $category, $summary, $description, $thumbnail, $tech, $github, $demo]);
        $message = "Project added successfully!";
        $active_tab = 'projects';
    }

    // ADD SKILL
    if (isset($_POST['add_skill'])) {
        $name = trim($_POST['name']);
        $category = $_POST['category'];
        $icon = trim($_POST['icon_class']);

        $stmt = $db->prepare("INSERT INTO skills (name, category, icon_class) VALUES (?, ?, ?)");
        $stmt->execute([$name, $category, $icon]);
        $message = "Skill added successfully!";
        $active_tab = 'skills';
    }

    // ADD EXPERIENCE
    if (isset($_POST['add_experience'])) {
        $position = trim($_POST['position']);
        $company = trim($_POST['company']);
        $location = trim($_POST['location']);
        $start_date = $_POST['start_date'];
        $end_date = !empty($_POST['end_date']) ? $_POST['end_date'] : null;
        $is_current = isset($_POST['is_current']) ? 1 : 0;
        $description = trim($_POST['description']);
        $tech = trim($_POST['technologies_used']);

        $stmt = $db->prepare("INSERT INTO experience (position, company, location, start_date, end_date, is_current, description, technologies_used) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$position, $company, $location, $start_date,$end_date, $is_current,$description, $tech]);$message = "Experience entry added!";
        $active_tab = 'experience';
    }

    // ADD EDUCATION
    if (isset($_POST['add_education'])) {
        $degree = trim($_POST['degree']);
        $institution = trim($_POST['institution']);
        $location = trim($_POST['location']);
        $start_year = (int)$_POST['start_year'];
        $end_year = !empty($_POST['end_year']) ? (int)$_POST['end_year'] : null;
        $description = trim($_POST['description']);

        $stmt = $db->prepare("
            INSERT INTO education (degree, institution, location, start_year, end_year, description) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$degree, $institution, $location, $start_year, $end_year, $description]);
        $message = "Education record added successfully!";
        $active_tab = 'education';
    }

    // ADD CERTIFICATION
    if (isset($_POST['add_certification'])) {
        $title = trim($_POST['title']);
        $issuer = trim($_POST['issuer']);
        $issue_date =$_POST['issue_date'];
        $cred_id = trim($_POST['credential_id']);
        $cred_url = trim($_POST['credential_url']);

        $badge = 'default-cert.png';
        if (!empty($_FILES['badge_image']['name']) && $_FILES['badge_image']['error'] === UPLOAD_ERR_OK) {
            $targetDir = __DIR__ . '/../assets/images/certificates/';
            
            // Create target directory if it doesn't exist
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            $ext = pathinfo($_FILES['badge_image']['name'], PATHINFO_EXTENSION);
            $badge = time() . '_cert.' . $ext;
            move_uploaded_file($_FILES['badge_image']['tmp_name'], $targetDir . $badge);
        }

        $stmt =$db->prepare("INSERT INTO certifications (title, issuer, issue_date, credential_id, credential_url, badge_image) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $issuer,$issue_date, $cred_id,$cred_url, $badge]);$message = "Certification record added!";
        $active_tab = 'certifications';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Portfolio Manager Dashboard</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <style>
    body { background-color: #0B0F14; color: #F8FAFC; font-family: 'Space Mono', monospace; padding: 2rem; }
    .admin-nav { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #263241; padding-bottom: 1rem; margin-bottom: 2rem; }
    .admin-tabs { display: flex; gap: 0.5rem; }
    .admin-tabs a { background: #151B23; border: 1px solid #263241; color: #94A3B8; padding: 0.5rem 1rem; border-radius: 4px; text-decoration: none; font-size: 0.85rem; }
    .admin-tabs a.active, .admin-tabs a:hover { background: #3B82F6; color: #fff; border-color: #3B82F6; }
    .btn-logout { background: #EF4444; color: #fff; padding: 0.5rem 1rem; border-radius: 4px; text-decoration: none; font-size: 0.85rem; font-weight: bold; }
    .alert-msg { background: #10B981; color: #0B0F14; padding: 0.75rem; border-radius: 4px; margin-bottom: 1.5rem; font-weight: bold; }
    .grid-admin { display: grid; grid-template-columns: 350px 1fr; gap: 2rem; }
    .form-card { background: #151B23; border: 1px solid #263241; padding: 1.5rem; border-radius: 8px; }
    .form-card h3 { color: #3B82F6; margin-bottom: 1rem; font-size: 1.1rem; }
    .form-group { margin-bottom: 0.85rem; }
    .form-group label { display: block; font-size: 0.75rem; color: #64748B; margin-bottom: 0.25rem; }
    .form-group input, .form-group textarea, .form-group select { width: 100%; padding: 0.5rem; background: #0B0F14; border: 1px solid #263241; color: #F8FAFC; border-radius: 4px; font-family: inherit; font-size: 0.85rem; }
    .btn-submit { width: 100%; padding: 0.6rem; background: #10B981; color: #0B0F14; border: none; border-radius: 4px; font-weight: bold; cursor: pointer; font-family: inherit; margin-top: 0.5rem; }
    .table-card { background: #151B23; border: 1px solid #263241; border-radius: 8px; padding: 1rem; overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; text-align: left; font-size: 0.8rem; }
    th, td { padding: 0.6rem; border-bottom: 1px solid #263241; }
    th { color: #64748B; }
    .btn-del { color: #EF4444; text-decoration: none; font-weight: bold; }
  </style>
</head>
<body>

  <!-- Admin Header Nav -->
  <header class="admin-nav">
    <div>
      <h1 style="font-size: 1.4rem; color: #F8FAFC;">PORTFOLIO_DATABASE_CMS</h1>
      <small style="color: #64748B;">User: <?php echo htmlspecialchars($_SESSION['admin_user']); ?></small>
    </div>

    <div class="admin-tabs">
      <a href="?tab=projects" class="<?php echo $active_tab === 'projects' ? 'active' : ''; ?>">Projects</a>
      <a href="?tab=skills" class="<?php echo $active_tab === 'skills' ? 'active' : ''; ?>">Skills</a>
      <a href="?tab=experience" class="<?php echo $active_tab === 'experience' ? 'active' : ''; ?>">Experience</a>
      <a href="?tab=education" class="<?php echo $active_tab === 'education' ? 'active' : ''; ?>">Education</a>
      <a href="?tab=certifications" class="<?php echo $active_tab === 'certifications' ? 'active' : ''; ?>">Certifications</a>
      <a href="?tab=messages" class="<?php echo $active_tab === 'messages' ? 'active' : ''; ?>">Messages</a>
    </div>

    <a href="?action=logout" class="btn-logout">LOGOUT</a>
  </header>

  <?php if (!empty($message)): ?>
    <div class="alert-msg">✔ <?php echo htmlspecialchars($message); ?></div>
  <?php endif; ?>

  <main>
    <!-- TAB 1: PROJECTS -->
    <?php if ($active_tab === 'projects'): ?>
      <div class="grid-admin">
        <div class="form-card">
          <h3>Insert New Project</h3>
          <form method="POST" enctype="multipart/form-data">
            <div class="form-group"><label>Title</label><input type="text" name="title" required></div>
            <div class="form-group">
              <label>Category</label>
              <select name="category">
                <option value="Web">Web</option>
                <option value="Mobile">Mobile</option>
                <option value="Business Systems">Business Systems</option>
                <option value="Academic">Academic</option>
              </select>
            </div>
            <div class="form-group"><label>Summary</label><input type="text" name="summary" required></div>
            <div class="form-group"><label>Full Description</label><textarea name="description" rows="3" required></textarea></div>
            <div class="form-group"><label>Technologies (e.g., PHP, MySQL, Tailwind)</label><input type="text" name="technologies" required></div>
            <div class="form-group"><label>GitHub URL</label><input type="url" name="github_url"></div>
            <div class="form-group"><label>Demo URL</label><input type="url" name="demo_url"></div>
            <div class="form-group"><label>Thumbnail Image</label><input type="file" name="thumbnail" accept="image/*"></div>
            <button type="submit" name="add_project" class="btn-submit">+ INSERT PROJECT</button>
          </form>
        </div>

        <div class="table-card">
          <table>
            <thead><tr><th>ID</th><th>Title</th><th>Category</th><th>Tech</th><th>Action</th></tr></thead>
            <tbody>
              <?php
              $rows =$db->query("SELECT * FROM projects ORDER BY id DESC")->fetchAll();
              foreach ($rows as$r):
              ?>
              <tr>
                <td><?php echo $r['id']; ?></td>
                <td><strong><?php echo htmlspecialchars($r['title']); ?></strong></td>
                <td><?php echo htmlspecialchars($r['category']); ?></td>
                <td><?php echo htmlspecialchars($r['technologies']); ?></td>
                <td><a href="?tab=projects&delete_table=projects&delete_id=<?php echo $r['id']; ?>" class="btn-del" onclick="return confirm('Delete this project?')">Delete</a></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    <?php endif; ?>

    <!-- TAB 2: SKILLS -->
    <?php if ($active_tab === 'skills'): ?>
      <div class="grid-admin">
        <div class="form-card">
          <h3>Insert Skill</h3>
          <form method="POST">
            <div class="form-group"><label>Skill Name</label><input type="text" name="name" required></div>
            <div class="form-group">
              <label>Category</label>
              <select name="category">
                <option value="Development">Development</option>
                <option value="Database">Database</option>
                <option value="Tools">Tools</option>
                <option value="Other">Other</option>
              </select>
            </div>
            <div class="form-group"><label>Icon Class (Optional)</label><input type="text" name="icon_class" placeholder="devicon-php-plain"></div>
            <button type="submit" name="add_skill" class="btn-submit">+ INSERT SKILL</button>
          </form>
        </div>

        <div class="table-card">
          <table>
            <thead><tr><th>ID</th><th>Name</th><th>Category</th><th>Action</th></tr></thead>
            <tbody>
              <?php
              $rows =$db->query("SELECT * FROM skills ORDER BY category, name ASC")->fetchAll();
              foreach ($rows as$r):
              ?>
              <tr>
                <td><?php echo $r['id']; ?></td>
                <td><strong><?php echo htmlspecialchars($r['name']); ?></strong></td>
                <td><?php echo htmlspecialchars($r['category']); ?></td>
                <td><a href="?tab=skills&delete_table=skills&delete_id=<?php echo $r['id']; ?>" class="btn-del" onclick="return confirm('Delete skill?')">Delete</a></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    <?php endif; ?>

    <!-- TAB 3: EXPERIENCE -->
    <?php if ($active_tab === 'experience'): ?>
      <div class="grid-admin">
        <div class="form-card">
          <h3>Insert Experience</h3>
          <form method="POST">
            <div class="form-group"><label>Position / Role</label><input type="text" name="position" required></div>
            <div class="form-group"><label>Company</label><input type="text" name="company" required></div>
            <div class="form-group"><label>Location</label><input type="text" name="location"></div>
            <div class="form-group"><label>Start Date</label><input type="date" name="start_date" required></div>
            <div class="form-group"><label>End Date (Leave blank if current)</label><input type="date" name="end_date"></div>
            <div class="form-group"><label><input type="checkbox" name="is_current" value="1"> Is Current Position</label></div>
            <div class="form-group"><label>Description</label><textarea name="description" rows="3" required></textarea></div>
            <div class="form-group"><label>Technologies Used</label><input type="text" name="technologies_used"></div>
            <button type="submit" name="add_experience" class="btn-submit">+ INSERT EXPERIENCE</button>
          </form>
        </div>

        <div class="table-card">
          <table>
            <thead><tr><th>ID</th><th>Position</th><th>Company</th><th>Dates</th><th>Action</th></tr></thead>
            <tbody>
              <?php
              $rows =$db->query("SELECT * FROM experience ORDER BY start_date DESC")->fetchAll();
              foreach ($rows as$r):
              ?>
              <tr>
                <td><?php echo $r['id']; ?></td>
                <td><strong><?php echo htmlspecialchars($r['position']); ?></strong></td>
                <td><?php echo htmlspecialchars($r['company']); ?></td>
                <td><?php echo $r['start_date']; ?> to <?php echo $r['is_current'] ? 'Present' :$r['end_date']; ?></td>
                <td><a href="?tab=experience&delete_table=experience&delete_id=<?php echo $r['id']; ?>" class="btn-del" onclick="return confirm('Delete entry?')">Delete</a></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    <?php endif; ?>

    <!-- TAB: EDUCATION -->
    <?php if ($active_tab === 'education'): ?>
      <div class="grid-admin">
        <!-- Education Input Form -->
        <div class="form-card">
          <h3>Insert Education</h3>
          <form method="POST">
            <div class="form-group">
              <label>Degree / Program</label>
              <input type="text" name="degree" placeholder="BS Information Technology" required>
            </div>
            
            <div class="form-group">
              <label>Institution / University</label>
              <input type="text" name="institution" placeholder="Our Lady of Fatima University" required>
            </div>

            <div class="form-group">
              <label>Location</label>
              <input type="text" name="location" placeholder="Valenzuela City, Philippines">
            </div>

            <div class="form-group">
              <label>Start Year</label>
              <input type="number" name="start_year" min="2000" max="2099" placeholder="2022" required>
            </div>

            <div class="form-group">
              <label>End Year (Leave blank if currently enrolled)</label>
              <input type="number" name="end_year" min="2000" max="2099" placeholder="2026">
            </div>

            <div class="form-group">
              <label>Description / Specialization Highlights</label>
              <textarea name="description" rows="3" placeholder="Specialized in Web Development, Database Systems..."></textarea>
            </div>

            <button type="submit" name="add_education" class="btn-submit">+ INSERT EDUCATION</button>
          </form>
        </div>

        <!-- Education Records Table -->
        <div class="table-card">
          <h3>Academic History</h3>
          <table>
            <thead>
              <tr>
                <th>ID</th>
                <th>Degree</th>
                <th>Institution</th>
                <th>Years</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $rows = $db->query("SELECT * FROM education ORDER BY start_year DESC")->fetchAll();
              if (!empty($rows)):
                foreach ($rows as $r):
              ?>
                <tr>
                  <td><?php echo $r['id']; ?></td>
                  <td><strong><?php echo htmlspecialchars($r['degree']); ?></strong></td>
                  <td><?php echo htmlspecialchars($r['institution']); ?></td>
                  <td><?php echo htmlspecialchars($r['start_year']); ?> - <?php echo htmlspecialchars($r['end_year'] ?? 'Present'); ?></td>
                  <td>
                    <a href="?tab=education&delete_table=education&delete_id=<?php echo $r['id']; ?>" 
                       class="btn-del" 
                       onclick="return confirm('Delete this education record?')">Delete</a>
                  </td>
                </tr>
              <?php 
                endforeach;
              else:
              ?>
                <tr>
                  <td colspan="5" style="text-align: center; color: #64748B;">No education records found in database.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    <?php endif; ?>

    <!-- TAB 4: CERTIFICATIONS -->
    <?php if ($active_tab === 'certifications'): ?>
      <div class="grid-admin">
        <div class="form-card">
          <h3>Insert Certification</h3>
          <form method="POST" enctype="multipart/form-data">
            <div class="form-group"><label>Certificate Title</label><input type="text" name="title" required></div>
            <div class="form-group"><label>Issuer / Organization</label><input type="text" name="issuer" required></div>
            <div class="form-group"><label>Issue Date</label><input type="date" name="issue_date" required></div>
            <div class="form-group"><label>Credential ID</label><input type="text" name="credential_id"></div>
            <div class="form-group"><label>Verification URL</label><input type="url" name="credential_url"></div>
            <div class="form-group"><label>Badge Image</label><input type="file" name="badge_image" accept="image/*"></div>
            <button type="submit" name="add_certification" class="btn-submit">+ INSERT CERTIFICATE</button>
          </form>
        </div>

        <div class="table-card">
          <table>
            <thead><tr><th>ID</th><th>Title</th><th>Issuer</th><th>Date</th><th>Action</th></tr></thead>
            <tbody>
              <?php
              $rows =$db->query("SELECT * FROM certifications ORDER BY issue_date DESC")->fetchAll();
              foreach ($rows as$r):
              ?>
              <tr>
                <td><?php echo $r['id']; ?></td>
                <td><strong><?php echo htmlspecialchars($r['title']); ?></strong></td>
                <td><?php echo htmlspecialchars($r['issuer']); ?></td>
                <td><?php echo $r['issue_date']; ?></td>
                <td><a href="?tab=certifications&delete_table=certifications&delete_id=<?php echo $r['id']; ?>" class="btn-del" onclick="return confirm('Delete certificate?')">Delete</a></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    <?php endif; ?>

    <!-- TAB 5: MESSAGES -->
    <?php if ($active_tab === 'messages'): ?>
      <div class="table-card">
        <h3>Contact Form Submissions</h3>
        <table>
          <thead><tr><th>Date</th><th>Name</th><th>Email</th><th>Subject</th><th>Message</th><th>IP</th><th>Action</th></tr></thead>
          <tbody>
            <?php
            $rows =$db->query("SELECT * FROM messages ORDER BY id DESC")->fetchAll();
            foreach ($rows as$r):
            ?>
            <tr>
              <td><small><?php echo $r['created_at']; ?></small></td>
              <td><strong><?php echo htmlspecialchars($r['name']); ?></strong></td>
              <td><a href="mailto:<?php echo htmlspecialchars($r['email']); ?>" style="color: #3B82F6;"><?php echo htmlspecialchars($r['email']); ?></a></td>
              <td><?php echo htmlspecialchars($r['subject']); ?></td>
              <td><?php echo nl2br(htmlspecialchars($r['message'])); ?></td>
              <td><small><?php echo htmlspecialchars($r['ip_address']); ?></small></td>
              <td><a href="?tab=messages&delete_table=messages&delete_id=<?php echo $r['id']; ?>" class="btn-del" onclick="return confirm('Delete message?')">Delete</a></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </main>

</body>
</html>
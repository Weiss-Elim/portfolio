<?php
// index.php
require_once __DIR__ . '/../config/database.php';

// Initialize DB connection safely
$db = null;
try {
    $db = getDBConnection();
} catch (Exception $e) {
    // Continue rendering with empty dataset or static fallbacks if DB connection fails
    error_log("Database Connection Failed: " . $e->getMessage());
}

// 1. Fetch Skills
$skills = [];
if ($db) {
    $stmt = $db->query("SELECT * FROM skills ORDER BY category, sort_order ASC");
    $skills = $stmt->fetchAll();
}

// Group skills by category
$categorizedSkills = [];
foreach ($skills as $skill) {
    $categorizedSkills[$skill['category']][] = $skill;
}

// 2. Fetch Featured / Published Projects
$projects = [];
if ($db) {
    $stmt = $db->query("SELECT * FROM projects WHERE status = 'published' ORDER BY sort_order ASC, id DESC");
    $projects = $stmt->fetchAll();
}

// 3. Fetch Work Experience
$experiences = [];
if ($db) {
    $stmt = $db->query("SELECT * FROM experience ORDER BY start_date DESC");
    $experiences = $stmt->fetchAll();
}

// 4. Fetch Education
$educationList = [];
if ($db) {
    $stmt = $db->query("SELECT * FROM education ORDER BY start_year DESC");
    $educationList = $stmt->fetchAll();
}

// Fetch Certifications
$certifications = [];
if ($db) {
    $stmt = $db->query("SELECT * FROM certifications ORDER BY issue_date DESC, sort_order ASC");
    $certifications = $stmt->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sean John A. Duque | IT Professional & Web Developer</title>
  <meta name="description" content="Portfolio of Sean John A. Duque, IT Professional & Web Developer specializing in practical web applications, business systems, and web solutions.">
  
  <!-- Fonts & Stylesheets -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="stylesheet" href="../assets/css/responsive.css">
</head>
<body>

  <main>
    <!-- 1. Hero / Home Section - Windows Start Menu Theme -->
    <section id="home" class="container hero-start-container">
      <div class="start-menu-window">
        
        <!-- Start Menu Header / User Profile Header -->
        <div class="start-header">
          <div class="user-profile">
            <div class="avatar-wrapper">
              <img src="../assets/images/profile.jpg" 
                   alt="Sean John A. Duque" 
                   class="user-avatar">
              <span class="status-indicator online" title="System Status: Online"></span>
            </div>
            <div class="user-info">
              <h1 class="user-name">Sean John A. Duque</h1>
              <p class="user-title">IT Professional &amp; Web Developer</p>
              <span class="system-path">C:\Users\SeanDuque (Logged In)</span>
            </div>
          </div>

          <div class="header-power">
            <span class="power-status">
              <span class="icon">⚡</span> System Status: <strong>Ready for Hire</strong>
            </span>
          </div>
        </div>

        <!-- Start Menu Content Body -->
        <div class="start-body">
          
          <!-- Left Column: Pinned Applications & Quick Launch -->
          <div class="start-pinned">
            <div class="section-label">
              <span>Pinned Apps &amp; Quick Launch</span>
            </div>

            <div class="pinned-grid">
              <a href="#projects" class="pinned-app">
                <div class="app-icon accent-blue">📁</div>
                <div class="app-info">
                  <span class="app-name">Projects/</span>
                  <span class="app-desc">View Portfolio Directory</span>
                </div>
              </a>

              <a href="#about" class="pinned-app">
                <div class="app-icon accent-green">📄</div>
                <div class="app-info">
                  <span class="app-name">AboutMe.sys</span>
                  <span class="app-desc">System Credentials &amp; Info</span>
                </div>
              </a>

              <a href="#skills" class="pinned-app">
                <div class="app-icon accent-purple">⚙️</div>
                <div class="app-info">
                  <span class="app-name">Skills/</span>
                  <span class="app-desc">Technical Stack &amp; Tools</span>
                </div>
              </a>

              <a href="#experience" class="pinned-app">
                <div class="app-icon accent-amber">💼</div>
                <div class="app-info">
                  <span class="app-name">Work_History/</span>
                  <span class="app-desc">Experience &amp; Career Log</span>
                </div>
              </a>

              <a href="#education" class="pinned-app">
                <div class="app-icon accent-teal">🎓</div>
                <div class="app-info">
                  <span class="app-name">Education/</span>
                  <span class="app-desc">Academic Background</span>
                </div>
              </a>

              <a href="#certifications" class="pinned-app">
                <div class="app-icon accent-red">🏆</div>
                <div class="app-info">
                  <span class="app-name">Certifications/</span>
                  <span class="app-desc">Professional Certifications</span>
                </div>
              </a>
            </div>
          </div>

          <!-- Right Column: System Specs & Resume Shortcut -->
          <aside class="start-sidebar card">
            <div class="sidebar-title">
              <span class="icon">💻</span> System Specifications
            </div>

            <div class="spec-list">
              <div class="spec-item">
                <span class="spec-key">Primary Stack</span>
                <span class="spec-val">PHP 8+ • MySQL • JS</span>
              </div>
              <div class="spec-item">
                <span class="spec-key">Frameworks</span>
                <span class="spec-val">Laravel • Livewire • Tailwind</span>
              </div>
              <div class="spec-item">
                <span class="spec-key">Degree</span>
                <span class="spec-val">BS Information Technology</span>
              </div>
              <div class="spec-item">
                <span class="spec-key">Location</span>
                <span class="spec-val">Abu Dhabi, United Arab Emirates</span>
              </div>
            </div>

            <div class="resume-launch">
              <a href="../assets/documents/resume.pdf" target="_blank" class="btn-start-action">
                <span class="icon">📥</span> Download Resume.pdf
              </a>
            </div>
          </aside>

        </div>

        <!-- Start Menu Footer / Account Control Bar -->
        <div class="start-footer">
          <div class="footer-account">
            <span class="icon">🛡️</span> Administrator Session
          </div>
          <div class="footer-actions">
            <a href="https://www.linkedin.com/in/sean-john-duque" class="footer-btn">
              <span class="icon">💬</span> Let's Connect
            </a>
          </div>
        </div>

      </div>
    </section>

    <!-- 2. About Me Section Window -->
    <section id="about" class="container">
      <div class="explorer-window">
        <!-- Titlebar -->
        <div class="window-titlebar">
          <div class="window-title">
            <span class="icon">📁</span>
            <span>File Explorer — C:/Portfolio/AboutMe</span>
          </div>
          <div class="window-controls">
            <span class="win-btn minimize"></span>
            <span class="win-btn maximize"></span>
            <span class="win-btn close"></span>
          </div>
        </div>

        <!-- Address Bar -->
        <div class="window-addressbar">
          <span>Location:</span>
          <div class="address-path">C:\Users\SeanDuque\Portfolio\about_me.sys</div>
        </div>

        <!-- Window Body -->
        <div class="window-body">
          <!-- Sidebar -->
          <aside class="explorer-sidebar">
            <div class="sidebar-heading">Quick Access</div>
            <ul class="sidebar-tree">
              <li><a href="#home">🏠 Root (C:)</a></li>
              <li class="active"><a href="#about">📄 about_me.sys</a></li>
              <li><a href="#skills">📁 Skills/</a></li>
              <li><a href="#projects">📁 Projects/</a></li>
              <li><a href="#experience">📁 Work_History/</a></li>
              <li><a href="#education">📁 Education/</a></li>
              <li><a href="#certifications">📁 Certifications/</a></li>
            </ul>
          </aside>

          <!-- Main Workspace -->
          <div class="explorer-content">
            <h2 class="section-title">System Properties — About Me</h2>
            <p class="explorer-subtext">C:\Users\SeanDuque\Portfolio\about_me.sys</p>

            <div class="about-sys-wrapper">
              
              <!-- System Summary Header Stats -->
              <div class="sys-metrics-grid">
                <div class="metric-card card">
                  <span class="metric-icon">💻</span>
                  <div class="metric-info">
                    <span class="metric-val">BS IT</span>
                    <span class="metric-label">Education</span>
                  </div>
                </div>

                <div class="metric-card card">
                  <span class="metric-icon">⚙️</span>
                  <div class="metric-info">
                    <span class="metric-val">PHP / TALL</span>
                    <span class="metric-label">Primary Stack</span>
                  </div>
                </div>

                <div class="metric-card card">
                  <span class="metric-icon">🚀</span>
                  <div class="metric-info">
                    <span class="metric-val">Full-Stack</span>
                    <span class="metric-label">Core Focus</span>
                  </div>
                </div>
              </div>

              <!-- Main Diagnostic Grid -->
              <div class="grid-2 about-sys-grid">
                
                <!-- Left Column: Biography & Capabilities -->
                <div class="about-bio-card card">
                  <div class="card-sys-header">
                    <span class="icon">📄</span> Overview
                  </div>
                  <p class="bio-text">
                    Information Technology graduate specializing in web development, 
                    database systems, and digital solutions. I build practical applications 
                    that solve real-world business needs.
                  </p>

                  <div class="capabilities-group">
                    <span class="cap-title">Core Competencies:</span>
                    <ul class="cap-list">
                      <li><span>✓</span> Web application development &amp; REST APIs</li>
                      <li><span>✓</span> Relational database design &amp; schema normalization</li>
                      <li><span>✓</span> Business management, inventory &amp; POS systems</li>
                      <li><span>✓</span> Network administration &amp; technical IT support</li>
                    </ul>
                  </div>
                </div>

                <!-- Right Column: System Specifications (Quick Details) -->
                <div class="about-specs-card card">
                  <div class="card-sys-header">
                    <span class="icon">🖥️</span> System Specifications
                  </div>

                  <div class="spec-table">
                    <div class="spec-row">
                      <span class="spec-key">Full Name</span>
                      <span class="spec-val">Sean John A. Duque</span>
                    </div>
                    <div class="spec-row">
                      <span class="spec-key">Role / Title</span>
                      <span class="spec-val">IT Professional &amp; Web Developer</span>
                    </div>
                    <div class="spec-row">
                      <span class="spec-key">Degree</span>
                      <span class="spec-val">BS in Information Technology</span>
                    </div>
                    <div class="spec-row">
                      <span class="spec-key">Location</span>
                      <span class="spec-val">Abu Dhabi, United Arab Emirates</span>
                    </div>
                    <div class="spec-row">
                      <span class="spec-key">Deployment</span>
                      <span class="spec-val highlight-green">Available Immediately</span>
                    </div>
                  </div>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- 3. Skills Section Window -->
    <section id="skills" class="container">
      <div class="explorer-window">
        <div class="window-titlebar">
          <div class="window-title">
            <span class="icon">📁</span>
            <span>File Explorer — C:/Portfolio/Skills</span>
          </div>
          <div class="window-controls">
            <span class="win-btn minimize"></span>
            <span class="win-btn maximize"></span>
            <span class="win-btn close"></span>
          </div>
        </div>

        <div class="window-addressbar">
          <span>Location:</span>
          <div class="address-path">C:\Users\SeanDuque\Portfolio\Skills\competencies.dll</div>
        </div>

        <div class="window-body">
          <aside class="explorer-sidebar">
            <div class="sidebar-heading">Quick Access</div>
            <ul class="sidebar-tree">
              <li><a href="#home">🏠 Root (C:)</a></li>
              <li><a href="#about">📄 about_me.sys</a></li>
              <li class="active"><a href="#skills">📁 Skills/</a></li>
              <li><a href="#projects">📁 Projects/</a></li>
              <li><a href="#experience">📁 Work_History/</a></li>
              <li><a href="#education">📁 Education/</a></li>
              <li><a href="#certifications">📁 Certifications/</a></li>
            </ul>
          </aside>

          <!-- Main Workspace -->
          <div class="explorer-content">
            <h2 class="section-title">Technical Competencies</h2>
            <p class="explorer-subtext">Categorized technical skills, frameworks, and tools.</p>

            <div class="skills-category-grid">
              <?php if (!empty($categorizedSkills)): ?>
                <?php foreach ($categorizedSkills as $categoryName => $skillGroup): ?>
                  <div class="skill-category-card card">
                    <div class="category-header">
                      <span class="icon">📁</span>
                      <h3><?php echo htmlspecialchars($categoryName); ?></h3>
                    </div>
                    <div class="skills-flex-wrap">
                      <?php foreach ($skillGroup as $skill): ?>
                        <div class="skill-tag">
                          <?php if (!empty($skill['icon_class'])): ?>
                            <i class="<?php echo htmlspecialchars($skill['icon_class']); ?>"></i>
                          <?php endif; ?>
                          <span><?php echo htmlspecialchars($skill['name']); ?></span>
                        </div>
                      <?php endforeach; ?>
                    </div>
                  </div>
                <?php endforeach; ?>
              <?php else: ?>
                <!-- Static Fallback Grid Layout -->
                <div class="skill-category-card card">
                  <div class="category-header">
                    <span class="icon">📁</span>
                    <h3>Development</h3>
                  </div>
                  <div class="skills-flex-wrap">
                    <span class="skill-tag">PHP</span>
                    <span class="skill-tag">Laravel</span>
                    <span class="skill-tag">Livewire</span>
                    <span class="skill-tag">Alpine.js</span>
                    <span class="skill-tag">Vue.js</span>
                    <span class="skill-tag">React</span>
                    <span class="skill-tag">CSS / Tailwind</span>
                    <span class="skill-tag">Objective-C</span>
                    <span class="skill-tag">C++</span>
                    <span class="skill-tag">C#</span>
                    <span class="skill-tag">C</span>
                    <span class="skill-tag">Java</span>
                    <span class="skill-tag">Python</span>
                    <span class="skill-tag">Flutter</span>
                    <span class="skill-tag">Kotlin</span>
                  </div>
                </div>

                <div class="skill-category-card card">
                  <div class="category-header">
                    <span class="icon">📁</span>
                    <h3>Database</h3>
                  </div>
                  <div class="skills-flex-wrap">
                    <span class="skill-tag">MySQL</span>
                    <span class="skill-tag">Database Design</span>
                    <span class="skill-tag">CRUD</span>
                    <span class="skill-tag">Schema Normalization</span>
                  </div>
                </div>

                <div class="skill-category-card card">
                  <div class="category-header">
                    <span class="icon">📁</span>
                    <h3>Tools & Platform</h3>
                  </div>
                  <div class="skills-flex-wrap">
                    <span class="skill-tag">Git</span>
                    <span class="skill-tag">GitHub</span>
                    <span class="skill-tag">WordPress</span>
                    <span class="skill-tag">VS Code</span>
                    <span class="skill-tag">XAMPP</span>
                  </div>
                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </section>

<!-- 4. Projects Section Window -->
    <section id="projects" class="container">
      <div class="explorer-window">
        <!-- Titlebar -->
        <div class="window-titlebar">
          <div class="window-title">
            <span class="icon">📁</span>
            <span>File Explorer — C:/Portfolio/Projects</span>
          </div>
          <div class="window-controls">
            <span class="win-btn minimize"></span>
            <span class="win-btn maximize"></span>
            <span class="win-btn close"></span>
          </div>
        </div>

        <!-- Address Bar -->
        <div class="window-addressbar">
          <span>Location:</span>
          <div class="address-path">C:\Users\SeanDuque\Portfolio\Projects\repository</div>
        </div>

        <!-- Window Body -->
        <div class="window-body">
          <!-- Sidebar -->
          <aside class="explorer-sidebar">
            <div class="sidebar-heading">Quick Access</div>
            <ul class="sidebar-tree">
              <li><a href="#home">🏠 Root (C:)</a></li>
              <li><a href="#about">📄 about_me.sys</a></li>
              <li><a href="#skills">📁 Skills/</a></li>
              <li class="active"><a href="#projects">📁 Projects/</a></li>
              <li><a href="#experience">📁 Work_History/</a></li>
              <li><a href="#education">📁 Education/</a></li>
              <li><a href="#certifications">📁 Certifications/</a></li>
            </ul>
          </aside>

          <!-- Main Workspace -->
          <div class="explorer-content">
            <h2 class="section-title">Projects Directory</h2>
            <p class="explorer-subtext">Double-click or select a file item to preview project details.</p>

            <!-- Grid of Image File Thumbnails -->
            <div class="file-grid">
              <?php if (!empty($projects)): ?>
                <?php foreach ($projects as $project): ?>
                  <div class="file-item project-item" 
                       data-title="<?php echo htmlspecialchars($project['title']); ?>"
                       data-category="<?php echo htmlspecialchars($project['category']); ?>"
                       data-summary="<?php echo htmlspecialchars($project['summary']); ?>"
                       data-tech="<?php echo htmlspecialchars($project['technologies']); ?>"
                       data-img="../assets/images/projects/<?php echo htmlspecialchars($project['thumbnail']); ?>"
                       data-github="<?php echo htmlspecialchars($project['github_url'] ?? ''); ?>"
                       data-demo="<?php echo htmlspecialchars($project['demo_url'] ?? ''); ?>">
                    <div class="file-thumbnail">
                      <img src="../assets/images/projects/<?php echo htmlspecialchars($project['thumbnail']); ?>" 
                           alt="<?php echo htmlspecialchars($project['title']); ?>">
                      <span class="file-badge">IMG</span>
                    </div>
                    <div class="file-details">
                      <span class="file-name"><?php echo htmlspecialchars($project['slug'] ?? 'project'); ?>.png</span>
                      <span class="file-meta"><?php echo htmlspecialchars($project['category']); ?></span>
                    </div>
                  </div>
                <?php endforeach; ?>
              <?php else: ?>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- 5. Experience Section Window -->
    <section id="experience" class="container">
      <div class="explorer-window">
        <!-- Titlebar -->
        <div class="window-titlebar">
          <div class="window-title">
            <span class="icon">📁</span>
            <span>File Explorer — C:/Portfolio/Work_History</span>
          </div>
          <div class="window-controls">
            <span class="win-btn minimize"></span>
            <span class="win-btn maximize"></span>
            <span class="win-btn close"></span>
          </div>
        </div>

        <!-- Address Bar -->
        <div class="window-addressbar">
          <span>Location:</span>
          <div class="address-path">C:\Users\SeanDuque\Portfolio\Work_History\career_log.log</div>
        </div>

        <!-- Window Body -->
        <div class="window-body">
          <!-- Sidebar -->
          <aside class="explorer-sidebar">
            <div class="sidebar-heading">Quick Access</div>
            <ul class="sidebar-tree">
              <li><a href="#home">🏠 Root (C:)</a></li>
              <li><a href="#about">📄 about_me.sys</a></li>
              <li><a href="#skills">📁 Skills/</a></li>
              <li><a href="#projects">📁 Projects/</a></li>
              <li class="active"><a href="#experience">📁 Work_History/</a></li>
              <li><a href="#education">📁 Education/</a></li>
              <li><a href="#certifications">📁 Certifications/</a></li>
            </ul>
          </aside>

          <!-- Main Workspace -->
          <div class="explorer-content">
            <h2 class="section-title">Work Experience</h2>
            <p class="explorer-subtext">C:\Users\SeanDuque\Portfolio\Work_History\career_log.log</p>

            <div class="timeline-log-wrapper">
              <?php if (!empty($experiences)): ?>
                <?php foreach ($experiences as $exp): ?>
                  <div class="log-entry card">
                    <div class="log-node"></div>
                    <div class="log-header">
                      <div class="log-title-group">
                        <h3><?php echo htmlspecialchars($exp['position']); ?></h3>
                        <h4><?php echo htmlspecialchars($exp['company']); ?> <?php echo !empty($exp['location']) ? '• <span class="loc">' . htmlspecialchars($exp['location']) . '</span>' : ''; ?></h4>
                      </div>
                      <span class="log-date-badge">
                        <?php echo date('M Y', strtotime($exp['start_date'])); ?> – 
                        <?php echo ($exp['is_current'] || empty($exp['end_date'])) ? 'Present' : date('M Y', strtotime($exp['end_date'])); ?>
                      </span>
                    </div>

                    <div class="log-body">
                      <p><?php echo nl2br(htmlspecialchars($exp['description'])); ?></p>
                      
                      <?php if (!empty($exp['technologies_used'])): ?>
                        <div class="log-tech-stack">
                          <span class="tech-label">Stack:</span>
                          <?php 
                            $techs = explode(',', $exp['technologies_used']);
                            foreach ($techs as $tech): 
                          ?>
                            <span class="tech-chip"><?php echo htmlspecialchars(trim($tech)); ?></span>
                          <?php endforeach; ?>
                        </div>
                      <?php endif; ?>
                    </div>
                  </div>
                <?php endforeach; ?>
              <?php else: ?>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- 6. Education Section Window -->
    <section id="education" class="container">
      <div class="explorer-window">
        <!-- Titlebar -->
        <div class="window-titlebar">
          <div class="window-title">
            <span class="icon">📁</span>
            <span>File Explorer — C:/Portfolio/Education</span>
          </div>
          <div class="window-controls">
            <span class="win-btn minimize"></span>
            <span class="win-btn maximize"></span>
            <span class="win-btn close"></span>
          </div>
        </div>

        <!-- Address Bar -->
        <div class="window-addressbar">
          <span>Location:</span>
          <div class="address-path">C:\Users\SeanDuque\Portfolio\Education\academic_records</div>
        </div>

        <!-- Window Body -->
        <div class="window-body">
          <!-- Sidebar -->
          <aside class="explorer-sidebar">
            <div class="sidebar-heading">Quick Access</div>
            <ul class="sidebar-tree">
              <li><a href="#home">🏠 Root (C:)</a></li>
              <li><a href="#about">📄 about_me.sys</a></li>
              <li><a href="#skills">📁 Skills/</a></li>
              <li><a href="#projects">📁 Projects/</a></li>
              <li><a href="#experience">📁 Work_History/</a></li>
              <li class="active"><a href="#education">📁 Education/</a></li>
              <li><a href="#certifications">📁 Certifications/</a></li>
            </ul>
          </aside>

          <!-- Main Workspace -->
          <div class="explorer-content">
            <h2 class="section-title">Education</h2>
            <p class="explorer-subtext">C:\Users\SeanDuque\Portfolio\Education\academic_records.log</p>

            <div class="timeline-log-wrapper">
              <?php if (!empty($educationList)): ?>
                <?php foreach ($educationList as $edu): ?>
                  <div class="log-entry card">
                    <div class="log-node"></div>
                    <div class="log-header">
                      <div class="log-title-group">
                        <h3><?php echo htmlspecialchars($edu['degree']); ?></h3>
                        <h4><?php echo htmlspecialchars($edu['institution']); ?> <?php echo !empty($edu['location']) ? '• <span class="loc">' . htmlspecialchars($edu['location']) . '</span>' : ''; ?></h4>
                      </div>
                      <span class="log-date-badge">
                        <?php echo htmlspecialchars($edu['start_year']); ?> – <?php echo htmlspecialchars($edu['end_year'] ?? 'Present'); ?>
                      </span>
                    </div>

                    <div class="log-body">
                      <?php if (!empty($edu['description'])): ?>
                        <p><?php echo nl2br(htmlspecialchars($edu['description'])); ?></p>
                      <?php endif; ?>
                    </div>
                  </div>
                <?php endforeach; ?>
              <?php else: ?>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Certifications Section Window -->
    <section id="certifications" class="container">
      <div class="explorer-window">
        <!-- Titlebar -->
        <div class="window-titlebar">
          <div class="window-title">
            <span class="icon">📁</span>
            <span>File Explorer — C:/Portfolio/Certifications</span>
          </div>
          <div class="window-controls">
            <span class="win-btn minimize"></span>
            <span class="win-btn maximize"></span>
            <span class="win-btn close"></span>
          </div>
        </div>

        <!-- Address Bar -->
        <div class="window-addressbar">
          <span>Location:</span>
          <div class="address-path">C:\Users\SeanDuque\Portfolio\Certifications\credentials.pfx</div>
        </div>

        <!-- Window Body -->
        <div class="window-body">
          <!-- Sidebar -->
          <aside class="explorer-sidebar">
            <div class="sidebar-heading">Quick Access</div>
            <ul class="sidebar-tree">
              <li><a href="#home">🏠 Root (C:)</a></li>
              <li><a href="#about">📄 about_me.sys</a></li>
              <li><a href="#skills">📁 Skills/</a></li>
              <li><a href="#projects">📁 Projects/</a></li>
              <li><a href="#experience">📁 Work_History/</a></li>
              <li><a href="#education">📁 Education/</a></li>
              <li class="active"><a href="#certifications">📁 Certifications/</a></li>
            </ul>
          </aside>

          <!-- Main Workspace -->
          <div class="explorer-content">
            <h2 class="section-title">Certifications &amp; Credentials</h2>
            <p class="explorer-subtext">Click any certificate file to open the document viewer.</p>

            <div class="file-grid cert-file-grid">
              <?php if (!empty($certifications)): ?>
                <?php foreach ($certifications as $cert): ?>
                  <div class="file-item cert-item"
                       data-title="<?php echo htmlspecialchars($cert['title']); ?>"
                       data-issuer="<?php echo htmlspecialchars($cert['issuer']); ?>"
                       data-date="<?php echo date('M Y', strtotime($cert['issue_date'])); ?>"
                       data-id="<?php echo htmlspecialchars($cert['credential_id'] ?? 'N/A'); ?>"
                       data-url="<?php echo htmlspecialchars($cert['credential_url'] ?? ''); ?>"
                       data-img="../assets/images/certificates/<?php echo htmlspecialchars($cert['badge_image']); ?>">
                    <div class="file-thumbnail cert-thumbnail">
                      <img src="../assets/images/certificates/<?php echo htmlspecialchars($cert['badge_image']); ?>" 
                           alt="<?php echo htmlspecialchars($cert['title']); ?>">
                      <span class="file-badge">PFX</span>
                    </div>
                    <div class="file-details">
                      <span class="file-name"><?php echo strtolower(preg_replace('/[^a-z0-9]/', '_', $cert['title'])); ?>.pfx</span>
                      <span class="file-meta"><?php echo htmlspecialchars($cert['issuer']); ?> • <?php echo date('Y', strtotime($cert['issue_date'])); ?></span>
                    </div>
                  </div>
                <?php endforeach; ?>
              <?php else: ?>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </section>

<?php 
// Include modular footer
require_once __DIR__ . '/../includes/footer.php'; 
?>
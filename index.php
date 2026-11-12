<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($_SESSION['fullname']) ?> — Personal Portfolio</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- Header & Navigation -->
    <header class="site-header">
        <div class="container">
            <nav class="nav">
                <a href="#" class="brand">PORTFO<span>LIO</span></a>
                <ul class="nav-links">
                    <li><a href="#about">About</a></li>
                    <li><a href="#skills">Skills</a></li>
                    <li><a href="#projects">Projects</a></li>
                    <li><a href="#contact">Contact</a></li>
                    <li><a href="logout.php" class="btn btn-small btn-outline-light">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container hero-content">
            <p class="hero-sub">Welcome to my portal</p>
            <h1>Hi, I'm <span style="color: var(--primary-violet);"><?= htmlspecialchars($_SESSION['fullname']) ?></span></h1>
            <p class="lead">College Student & Aspiring Web Developer building dynamic, secure, and responsive web applications using PHP, MySQL, and modern frontend tools.</p>
            <div class="hero-actions">
                <a href="#projects" class="btn btn-light">View Projects</a>
                <a href="#contact" class="btn btn-outline-light">Get in Touch</a>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="section section-dark">
        <div class="container">
            <h2 class="section-title">About <span class="dim">Me</span></h2>
            
            <div class="about-card">
                <div class="about-pic">
                    <?php if (!empty($_SESSION['profile_image'])): ?>
                        <img src="uploads/<?= htmlspecialchars($_SESSION['profile_image']) ?>" alt="Profile Picture">
                    <?php else: ?>
                        <div class="avatar-fallback"><?= strtoupper(substr($_SESSION['fullname'], 0, 1)) ?></div>
                    <?php endif; ?>
                </div>
                <div class="about-text">
                    <h3 class="about-name"><?= htmlspecialchars($_SESSION['fullname']) ?></h3>
                    <p class="about-bio">Hello! I am a passionate IT/CS student focusing on backend integration, session management, database optimization, and clean UI design.</p>
                    <a href="#contact" class="about-btn">Contact Me</a>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="stat-grid">
                <div class="stat">
                    <div class="stat-icon">📁</div>
                    <div>
                        <div class="stat-label">Total Projects</div>
                        <div class="stat-value">6 Completed</div>
                    </div>
                </div>
                <div class="stat">
                    <div class="stat-icon">🎓</div>
                    <div>
                        <div class="stat-label">Course & School</div>
                        <div class="stat-value">BSCS - College</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Skills Section -->
    <section id="skills" class="section">
        <div class="container">
            <h2 class="section-title">Technical <span class="dim">Skills</span></h2>
            <ul class="skill-list">
                <li class="skill">HTML5 / CSS3</li>
                <li class="skill">PHP Backend</li>
                <li class="skill">MySQL Database</li>
                <li class="skill">JavaScript</li>
                <li class="skill">XAMPP Server</li>
            </ul>
        </div>
    </section>

    <!-- Projects Section (6 Projects) -->
    <section id="projects" class="section section-dark">
        <div class="container">
            <h2 class="section-title">Featured <span class="dim">Projects</span></h2>
            <div class="project-grid">
                
                <!-- Project 1 -->
                <div class="project-card">
                    <h3>Student Registration System</h3>
                    <p>An academic registry platform built with PHP and MySQL featuring user authentication, password hashing, and session management.</p>
                    <span class="project-tags">PHP / MySQL</span>
                </div>

                <!-- Project 2 -->
                <div class="project-card">
                    <h3>Inventory Management System</h3>
                    <p>An enterprise dashboard application designed to track stock items, suppliers, and daily transactional item movements.</p>
                    <span class="project-tags">PHP / MySQL</span>
                </div>

                <!-- Project 3 -->
                <div class="project-card">
                    <h3>Personal Portfolio Website</h3>
                    <p>A fully responsive digital resume platform featuring profile image uploads, secure access controls, and dynamic greeting.</p>
                    <span class="project-tags">Full-Stack</span>
                </div>

                <!-- Project 4 -->
                <div class="project-card">
                    <h3>Interactive Calculator App</h3>
                    <p>A web-based computational tool built with clean layouts and responsive JavaScript logic for immediate calculations.</p>
                    <span class="project-tags">JavaScript</span>
                </div>

                <!-- Project 5 -->
                <div class="project-card">
                    <h3>To-Do List Task Manager</h3>
                    <p>A productivity application to organize daily student tasks, track status checkboxes, and manage completion goals.</p>
                    <span class="project-tags">JavaScript / CSS</span>
                </div>

                <!-- Project 6 -->
                <div class="project-card">
                    <h3>CS Quiz Game App</h3>
                    <p>An engaging trivia game testing computer science fundamentals with instant scoring and interactive question flows.</p>
                    <span class="project-tags">JavaScript</span>
                </div>

            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="section">
        <div class="container">
            <h2 class="section-title">Get in <span class="dim">Touch</span></h2>
            <p class="contact-intro">Feel free to reach out for collaboration, project inquiries, or academic questions.</p>
            
            <div class="contact-cards">
                <div class="contact-card">
                    <div class="contact-icon">✉️</div>
                    <div>
                        <div class="contact-label">Email Address</div>
                        <div class="contact-value">KobeDelacruz@gmail.com</div>
                    </div>
                </div>
                <div class="contact-card">
                    <div class="contact-icon">🌐</div>
                    <div>
                        <div class="contact-label">Facebook Profile</div>
                        <div class="contact-value">Kobe Delacruz</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="site-footer">
        <div class="container">
            <p>&copy; <?= date('Y') ?> <?= htmlspecialchars($_SESSION['fullname']) ?>. All Rights Reserved.</p>
        </div>
    </footer>

</body>
</html>

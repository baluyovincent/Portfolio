<?php
require 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: signin.php');
    exit;
}

$stmt = $conn->prepare('SELECT fullname, username, profile_image, created_at FROM users WHERE id = ?');
$stmt->bind_param('i', $_SESSION['user_id']);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$user) {
    header('Location: logout.php');
    exit;
}

$course      = 'Bachelor of Science in Computer Science (BSCS)';
$courseShort = 'BSCS';
$school      = 'Kolehiyo ng Subic';
$schoolShort = 'KNS';
$yearLevel   = '2nd Year';
$experience  = 'Student';
$aboutGoal   = 'I want to explore the world of programming, web development, and expanding my knowledge in computer science.';
$goal        = 'I am still learning and exploring more about web development, and I hope to become a better developer in the future.';

$contactIntro = "Let's build something great together. Have a project in mind or just want to chat about web tech? I'm always open to discussing new opportunities, creative ideas, or ways to help your vision come to life.";
$phone        = '+63 991 422 1439';
$email        = 'KobeDelacruz@gmail.com';
$facebook     = 'Kobe Delacruz';

$skills = [
    ['HTML',   'Structuring web pages with clean, semantic markup.'],
    ['CSS',    'Responsive layouts, Flexbox, Grid, and consistent design.'],
    ['PHP',    'Dynamic pages, sessions, forms, and file uploads.'],
    ['MySQL',  'Designing tables and writing safe prepared queries.'],
    ['Python', 'Scripting and problem solving with simple programs.'],
];

$projects = [
    [
        'Student Information System',
        'Displays student information using PHP variables and HTML/CSS.',
        'PHP • HTML • CSS',
    ],
    [
        'Sign-Up and Sign-In System',
        'A basic login and registration system that accepts user input.',
        'PHP • HTML • CSS',
    ],
    [
        'Personal Portfolio Website',
        'A personal website showcasing my skills, achievements, and projects.',
        'HTML • CSS • PHP • MySQL',
    ],
    [
        'Online Quiz System',
        'Displays multiple-choice questions and calculates the user\'s score.',
        'HTML • CSS • JavaScript',
    ],
    [
        'Simple Calculator App',
        'An interactive web calculator built with dynamic DOM manipulation.',
        'HTML • CSS • JavaScript',
    ],
    [
        'Student Attendance System',
        'A system for tracking and managing student attendance records.',
        'HTML • MySQL • CSS',
    ],
];

$imageFile = $user['profile_image'] ? basename($user['profile_image']) : '';
$hasImage  = $imageFile !== '' && is_file(__DIR__ . '/uploads/' . $imageFile);
$initial   = strtoupper(mb_substr($user['fullname'], 0, 1));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($user['fullname']) ?> | Portfolio</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header class="site-header">
        <div class="container nav">
            <a class="brand" href="#home">MY<span>PORTFOLIO</span></a>
            <nav aria-label="Main navigation">
                <ul class="nav-links">
                    <li><a href="#home">Home</a></li>
                    <li><a href="#about">About</a></li>
                    <li><a href="#skills">Skills</a></li>
                    <li><a href="#projects">Projects</a></li>
                    <li><a href="#contact">Contact</a></li>
                    <li><a class="btn btn-small" href="logout.php" onclick="return confirm('Are you sure you want to log out?');">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        
        <!-- HERO SECTION (Walang picture sa gilid) -->
        <section id="home" class="hero">
            <div class="container hero-content">
                <p class="hero-sub">WELCOME TO MY PORTFOLIO</p>
                <h1>Hi, I'm <span class="highlight"><?= e($user['fullname']) ?></span></h1>
                <p class="lead"><?= e($goal) ?></p>
                <div class="hero-actions">
                    <a class="btn btn-light" href="#projects">View my work</a>
                    <a class="btn btn-outline-light" href="#contact">Contact me</a>
                </div>
            </div>
        </section>

        <!-- ABOUT SECTION -->
        <section id="about" class="section">
            <div class="container">
                <h2 class="section-title">About Me</h2>

                <!-- Malaking puting box sa taas -->
                <div class="about-card">
                    <div class="about-pic">
                        <?php if ($hasImage): ?>
                            <img src="uploads/<?= e($imageFile) ?>" alt="<?= e($user['fullname']) ?>">
                        <?php else: ?>
                            <div class="avatar-fallback" aria-hidden="true"><?= e($initial) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="about-text">
                        <h3 class="about-name"><?= e($user['fullname']) ?></h3>
                        <p class="about-bio">
                            I am <?= e($yearLevel) ?> student currently studying <?= e($course) ?> at <?= e($school) ?>.
                            <?= e($aboutGoal) ?>
                        </p>
                        <a class="about-btn" href="#projects">View Projects</a>
                    </div>
                </div>

                <!-- Stat grid na nakabukod sa labas para humaba at mapantay -->
                <div class="stat-grid">
                    <div class="stat">
                        <div class="stat-icon">
                            <svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 7a2 2 0 0 1 2-2h4l2 2h8a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                        </div>
                        <div>
                            <p class="stat-label">Total Projects</p>
                            <p class="stat-value"><?= count($projects) ?></p>
                        </div>
                    </div>

                    <div class="stat">
                        <div class="stat-icon">
                            <svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 10 12 5 2 10l10 5 10-5z"/><path d="M6 12v5c3 2 9 2 12 0v-5"/></svg>
                        </div>
                        <div>
                            <p class="stat-label">Course &amp; School</p>
                            <p class="stat-value"><?= e($courseShort) ?> - <?= e($schoolShort) ?></p>
                        </div>
                    </div>

                    <div class="stat">
                        <div class="stat-icon">
                            <svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M2 12h20"/><path d="M12 2a15 15 0 0 1 0 20 15 15 0 0 1 0-20z"/></svg>
                        </div>
                        <div>
                            <p class="stat-label">Experience</p>
                            <p class="stat-value"><?= e($experience) ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- SKILLS SECTION -->
        <section id="skills" class="section section-dark">
            <div class="container">
                <h2 class="sec-title"><span class="dim">My</span> Skills</h2>
                <div class="skill-list">
                    <?php foreach ($skills as $skill): ?>
                        <div class="skill" title="<?= e($skill[1]) ?>"><?= e($skill[0]) ?></div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <!-- PROJECTS SECTION -->
        <section id="projects" class="section">
            <div class="container">
                <h2 class="sec-title">Projects</h2>
                <div class="project-grid">
                    <?php foreach ($projects as $project): ?>
                        <article class="project-card">
                            <h3><?= e($project[0]) ?></h3>
                            <p><?= e($project[1]) ?></p>
                            <span class="project-tags"><?= e($project[2]) ?></span>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <!-- CONTACT SECTION -->
        <section id="contact" class="section section-dark">
            <div class="container">
                <h2 class="sec-title sec-left"><span class="dim">Contact</span> Me</h2>
                <p class="contact-intro"><?= e($contactIntro) ?></p>

                <div class="contact-cards">
                    <div class="contact-card">
                        <div class="contact-icon">
                            <svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.12.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.58 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        </div>
                        <div>
                            <p class="contact-label">My Phone</p>
                            <span class="contact-value"><?= e($phone) ?></span>
                        </div>
                    </div>

                    <div class="contact-card">
                        <div class="contact-icon">
                            <svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-10 6L2 7"/></svg>
                        </div>
                        <div>
                            <p class="contact-label">My Email</p>
                            <span class="contact-value"><?= e($email) ?></span>
                        </div>
                    </div>

                    <div class="contact-card">
                        <div class="contact-icon">
                            <svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                        </div>
                        <div>
                            <p class="contact-label">My Facebook</p>
                            <span class="contact-value"><?= e($facebook) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="site-footer">
        <div class="container">
            <p>&copy; <?= date('Y') ?> <?= e($user['fullname']) ?>. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>

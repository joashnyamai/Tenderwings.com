<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tender Wings Home - Every Child Deserves Love</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;700&display=swap" rel="stylesheet">
  <style>
    :root 
    {
      --primary: #4361ee;
      --primary-light: #edf0ff;
      --secondary: #3f37c9;
      --accent: #4895ef;
      --danger: #f72585;
      --success: #4cc9f0;
      --warning: #f8961e;
      --dark: #212529;
      --light: #f8f9fa;
      --gray: #6c757d;
      --light-gray: #e9ecef;
      --border-radius: 12px;
      --box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
      --transition: all 0.3s ease;
    }
    
    body 
    {
      font-family: 'Poppins', sans-serif;
      background-color: var(--light);
      color: var(--dark);
      overflow-x: hidden;
    }
    
    .sidebar 
    {
      width: 280px;
      height: 100vh;
      background: linear-gradient(135deg, var(--secondary), var(--primary));
      position: fixed;
      top: 0;
      left: -280px;
      padding: 2rem 1.5rem;
      color: white;
      box-shadow: 2px 0 15px rgba(0, 0, 0, 0.1);
      transition: var(--transition);
      z-index: 1000;
      display: flex;
      flex-direction: column;
    }
    
    .sidebar.active 
    {
      left: 0;
    }
    
    .sidebar-header {
      text-align: center;
      margin-bottom: 2rem;
    }
    
    .sidebar-logo {
      width: 120px;
      height: auto;
      margin-bottom: 1rem;
    }
    
    .sidebar-title {
      font-family: 'Playfair Display', serif;
      font-weight: 700;
      font-size: 1.5rem;
      margin-bottom: 0.5rem;
    }
    
    .sidebar-menu {
      flex-grow: 1;
    }
    
    .sidebar-link 
    {
      display: flex;
      align-items: center;
      color: white;
      text-decoration: none;
      padding: 0.8rem 1rem;
      margin-bottom: 0.5rem;
      border-radius: var(--border-radius);
      transition: var(--transition);
    }
    
    .sidebar-link:hover 
    {
      background: rgba(255, 255, 255, 0.15);
      transform: translateX(5px);
    }
    
    .sidebar-link i 
    {
      font-size: 1.1rem;
      margin-right: 1rem;
      width: 24px;
      text-align: center;
    }
    
    .sidebar-footer 
    {
      padding-top: 1rem;
      border-top: 1px solid rgba(255, 255, 255, 0.1);
      text-align: center;
      font-size: 0.9rem;
      opacity: 0.8;
    }
    
    .sidebar-toggle 
    {
      position: fixed;
      top: 20px;
      left: 20px;
      background: var(--primary);
      color: white;
      border: none;
      border-radius: 50%;
      width: 50px;
      height: 50px;
      cursor: pointer;
      z-index: 1001;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      transition: var(--transition);
    }
    
    .sidebar-toggle:hover 
    {
      background: var(--secondary);
      transform: scale(1.1);
    }
    
    .hero-section 
    {
      background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('wallp.jpeg') no-repeat center center/cover;
      height: 100vh;
      display: flex;
      align-items: center;
      color: white;
      position: relative;
      overflow: hidden;
    }
    
    .hero-content 
    {
      max-width: 800px;
      margin: 0 auto;
      text-align: center;
      padding: 0 1rem;
      animation: fadeInUp 1s ease;
    }
    
    .hero-title 
    {
      font-family: 'Playfair Display', serif;
      font-size: 3.5rem;
      font-weight: 700;
      margin-bottom: 1.5rem;
      line-height: 1.2;
    }
    
    .hero-subtitle 
    {
      font-size: 1.2rem;
      margin-bottom: 2rem;
      opacity: 0.9;
    }
    
    .cta-buttons 
    {
      display: flex;
      justify-content: center;
      gap: 1rem;
      flex-wrap: wrap;
    }
    
    .btn-hero {
      padding: 0.8rem 2rem;
      font-size: 1.1rem;
      font-weight: 500;
      border-radius: 50px;
      transition: var(--transition);
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
    }
    
    .btn-hero-primary 
    {
      background: var(--primary);
      color: white;
      border: 2px solid var(--primary);
    }
    
    .btn-hero-primary:hover 
    {
      background: transparent;
      color: white;
      border-color: white;
      transform: translateY(-3px);
    }
    
    .btn-hero-secondary 
    {
      background: transparent;
      color: white;
      border: 2px solid white;
    }
    
    .btn-hero-secondary:hover 
    {
      background: white;
      color: var(--primary);
      transform: translateY(-3px);
    }
    
    .section 
    {
      padding: 5rem 0;
    }
    
    .section-title 
    {
      font-family: 'Playfair Display', serif;
      font-size: 2.5rem;
      font-weight: 700;
      margin-bottom: 3rem;
      text-align: center;
      position: relative;
    }
    
    .section-title::after 
    {
      content: '';
      position: absolute;
      bottom: -15px;
      left: 50%;
      transform: translateX(-50%);
      width: 80px;
      height: 4px;
      background: var(--primary);
      border-radius: 2px;
    }
    
    .section-subtitle 
    {
      font-size: 1.2rem;
      color: var(--gray);
      text-align: center;
      max-width: 700px;
      margin: 0 auto 3rem;
    }
    
    .about-card 
    {
      background: white;
      border-radius: var(--border-radius);
      padding: 2rem;
      box-shadow: var(--box-shadow);
      height: 100%;
      transition: var(--transition);
    }
    
    .about-card:hover 
    {
      transform: translateY(-10px);
    }
    
    .about-icon 
    {
      font-size: 2.5rem;
      color: var(--primary);
      margin-bottom: 1.5rem;
    }
    
    .team-card 
    {
      background: white;
      border-radius: var(--border-radius);
      overflow: hidden;
      box-shadow: var(--box-shadow);
      transition: var(--transition);
      text-align: center;
      margin-bottom: 1.5rem;
    }
    
    .team-card:hover 
    {
      transform: translateY(-10px);
    }
    
    .team-img 
    {
      width: 100%;
      height: 250px;
      object-fit: cover;
    }
    
    .team-body 
    {
      padding: 1.5rem;
    }
    
    .team-name 
    {
      font-weight: 600;
      margin-bottom: 0.5rem;
    }
    
    .team-position 
    {
      color: var(--primary);
      font-weight: 500;
      margin-bottom: 1rem;
    }
    
    .team-social {
      display: flex;
      justify-content: center;
      gap: 0.8rem;
    }
    
    .team-social a {
      color: var(--gray);
      transition: var(--transition);
    }
    
    .team-social a:hover {
      color: var(--primary);
    }
    
    /* Programs Section */
    .programs-section {
      background: var(--primary-light);
    }
    
    .program-card {
      background: white;
      border-radius: var(--border-radius);
      padding: 2rem;
      box-shadow: var(--box-shadow);
      height: 100%;
      transition: var(--transition);
      text-align: center;
    }
    
    .program-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
    }
    
    .program-icon {
      font-size: 2.5rem;
      color: var(--primary);
      margin-bottom: 1.5rem;
    }

    .stats-section 
    {
      background: linear-gradient(135deg, var(--primary), var(--secondary));
      color: white;
      padding: 5rem 0;
      position: relative;
      overflow: hidden;
    }
    
    .stat-item 
    {
      text-align: center;
      margin-bottom: 2rem;
      position: relative;
      z-index: 1;
    }
    
    .stat-icon 
    {
      font-size: 2.5rem;
      margin-bottom: 1rem;
      color: rgba(255, 255, 255, 0.2);
    }
    
    .stat-number {
      font-size: 3rem;
      font-weight: 700;
      margin-bottom: 0.5rem;
    }
    
    .stat-label {
      font-size: 1.1rem;
      opacity: 0.9;
    }

    .testimonial-card 
    {
      background: white;
      border-radius: var(--border-radius);
      padding: 2rem;
      box-shadow: var(--box-shadow);
      position: relative;
      margin-bottom: 1.5rem;
    }
    
    .testimonial-text 
    {
      font-style: italic;
      margin-bottom: 1.5rem;
      position: relative;
    }
    
    .testimonial-text::before 
    {
      content: '"';
      font-size: 4rem;
      position: absolute;
      top: -20px;
      left: -15px;
      color: var(--primary-light);
      font-family: serif;
      line-height: 1;
      z-index: 0;
    }
    
    .testimonial-author {
      display: flex;
      align-items: center;
    }
    
    .testimonial-img 
    {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      object-fit: cover;
      margin-right: 1rem;
    }
    
    .testimonial-name 
    {
      font-weight: 600;
      margin-bottom: 0.2rem;
    }
    
    .testimonial-role 
    {
      color: var(--gray);
      font-size: 0.9rem;
    }
    
    .gallery-section 
    {
      padding: 5rem 0;
    }
    
    .gallery-item 
    {
      position: relative;
      border-radius: var(--border-radius);
      overflow: hidden;
      margin-bottom: 1.5rem;
      box-shadow: var(--box-shadow);
      transition: var(--transition);
    }
    
    .gallery-item:hover 
    {
      transform: translateY(-5px);
    }
    
    .gallery-img 
    {
      width: 100%;
      height: 250px;
      object-fit: cover;
      transition: var(--transition);
    }
    
    .gallery-item:hover .gallery-img 
    {
      transform: scale(1.05);
    }
    
    .gallery-overlay 
    {
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      background: linear-gradient(to top, rgba(0, 0, 0, 0.7), transparent);
      padding: 1rem;
      color: white;
      opacity: 0;
      transition: var(--transition);
    }
    
    .gallery-item:hover .gallery-overlay 
    {
      opacity: 1;
    }
    
    .contact-section 
    {
      background: var(--primary-light);
    }
    
    .contact-card 
    {
      background: white;
      border-radius: var(--border-radius);
      padding: 2rem;
      box-shadow: var(--box-shadow);
      height: 100%;
    }
    
    .contact-info 
    {
      margin-bottom: 2rem;
    }
    
    .contact-item {
      display: flex;
      align-items: flex-start;
      margin-bottom: 1.5rem;
    }
    
    .contact-icon {
      font-size: 1.2rem;
      color: var(--primary);
      margin-right: 1rem;
      margin-top: 0.2rem;
    }
    
    .contact-form .form-control {
      padding: 0.8rem 1rem;
      border-radius: var(--border-radius);
      border: 1px solid var(--light-gray);
      margin-bottom: 1.5rem;
    }
    
    .contact-form .form-control:focus {
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
    }
    
    .social-links {
      display: flex;
      gap: 1rem;
      margin-top: 2rem;
    }
    
    .social-link 
    {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: var(--primary);
      color: white;
      transition: var(--transition);
    }
    
    .social-link:hover 
    {
      background: var(--secondary);
      transform: translateY(-3px);
    }
    
    .footer 
    {
      background: var(--dark);
      color: white;
      padding: 3rem 0 1rem;
    }
    
    .footer-logo {
      width: 150px;
      margin-bottom: 1.5rem;
    }
    
    .footer-about {
      margin-bottom: 1.5rem;
    }
    
    .footer-title {
      font-family: 'Playfair Display', serif;
      font-size: 1.5rem;
      margin-bottom: 1.5rem;
      position: relative;
    }
    
    .footer-title::after 
    {
      content: '';
      position: absolute;
      bottom: -10px;
      left: 0;
      width: 40px;
      height: 3px;
      background: var(--primary);
    }
    
    .footer-links {
      list-style: none;
      padding: 0;
    }
    
    .footer-links li {
      margin-bottom: 0.8rem;
    }
    
    .footer-links a {
      color: rgba(255, 255, 255, 0.7);
      text-decoration: none;
      transition: var(--transition);
      display: inline-block;
    }
    
    .footer-links a:hover {
      color: white;
      transform: translateX(5px);
    }
    
    .footer-bottom {
      border-top: 1px solid rgba(255, 255, 255, 0.1);
      padding-top: 1.5rem;
      margin-top: 2rem;
      text-align: center;
      font-size: 0.9rem;
      color: rgba(255, 255, 255, 0.5);
    }
    
    @keyframes fadeInUp 
    {
      from 
      {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    @media (max-width: 992px) 
    {
      .hero-title 
      {
        font-size: 2.8rem;
      }
      
      .section-title {
        font-size: 2.2rem;
      }
    }
    
    @media (max-width: 768px) {
      .hero-title {
        font-size: 2.2rem;
      }
      
      .hero-subtitle {
        font-size: 1rem;
      }
      
      .section-title {
        font-size: 2rem;
      }
      
      .btn-hero {
        padding: 0.7rem 1.5rem;
        font-size: 1rem;
      }
      
      .sidebar {
        width: 250px;
      }
    }
    
    @media (max-width: 576px) {
      .hero-title {
        font-size: 1.8rem;
      }
      
      .section {
        padding: 3rem 0;
      }
      
      .section-title {
        font-size: 1.8rem;
        margin-bottom: 2rem;
      }
      
      .cta-buttons {
        flex-direction: column;
        gap: 0.8rem;
      }
      
      .btn-hero {
        width: 100%;
        justify-content: center;
      }
    }
  </style>
</head>
<body>
  <div class="sidebar" id="sidebar">
    <div class="sidebar-header">
      <img src="logotw.png" alt="Tender Wings Home Logo" class="sidebar-logo">
      <div class="sidebar-title">Tender Wings Home</div>
    </div>
    
    <div class="sidebar-menu">
      <a href="#home" class="sidebar-link">
        <i class="fas fa-home"></i>
        <span>Home</span>
      </a>
      <a href="About.php" class="sidebar-link">
        <i class="fas fa-info-circle"></i>
        <span>About Us</span>
      </a>
      <a href="#programs" class="sidebar-link">
        <i class="fas fa-project-diagram"></i>
        <span>Our Programs</span>
      </a>
      <a href="#get-involved" class="sidebar-link">
        <i class="fas fa-hands-helping"></i>
        <span>Get Involved</span>
      </a>
      <a href="#gallery" class="sidebar-link">
        <i class="fas fa-images"></i>
        <span>Gallery</span>
      </a>
      <a href="#testimonials" class="sidebar-link">
        <i class="fas fa-quote-left"></i>
        <span>Testimonials</span>
      </a>
      <a href="contact.php" class="sidebar-link">
        <i class="fas fa-envelope"></i>
        <span>Contact Us</span>
      </a>
    </div>
    
    <div class="sidebar-footer">
      <p>© 2025 Tender Wings Home</p>
      <p>Bringing Hope to Children</p>
    </div>
  </div>

  <button class="sidebar-toggle" id="sidebarToggle">
    <i class="fas fa-bars"></i>
  </button>

  <section id="home" class="hero-section">
    <div class="hero-content">
      <h1 class="hero-title">Every Child Deserves a Future Filled with Love and Possibilities</h1>
      <p class="hero-subtitle">Join us in our mission to provide shelter, education, and hope to children in need</p>
      <div class="cta-buttons">
        <a href="login.php" class="btn btn-hero btn-hero-primary">
          <i class="fas fa-home"></i> Support a Child
        </a>
        <a href="login.php" class="btn btn-hero btn-hero-secondary">
          <i class="fas fa-hands-helping"></i> Become a Volunteer
        </a>
      </div>
    </div>
  </section>

  <section id="about" class="section">
    <div class="container">
      <h2 class="section-title">About Tender Wings Home</h2>
      <p class="section-subtitle">Our mission is to provide a sanctuary of hope, love, and transformation for every child in need</p>
      
      <div class="row">
        <div class="col-lg-6 mb-4">
          <div class="about-card">
            <i class="fas fa-bullseye about-icon"></i>
            <h3>Our Mission & Vision</h3>
            <p>To create a nurturing environment where every child can thrive, regardless of their background or circumstances. We envision a world where all children have access to love, education, and opportunities to reach their full potential.</p>
          </div>
        </div>
        <div class="col-lg-6 mb-4">
          <div class="about-card">
            <i class="fas fa-history about-icon"></i>
            <h3>Our Story</h3>
            <p>Founded in 2010, Tender Wings Home began as a small shelter with just five children. Today, we've grown into a comprehensive care facility helping hundreds of children annually through our various programs and initiatives.</p>
          </div>
        </div>
      </div>
      
      <h3 class="text-center mt-5 mb-4" style="font-family: 'Playfair Display', serif;">Meet Our Team</h3>
      
      <div class="row">
        <div class="col-md-4">
          <div class="team-card">
            <img src="logotw.png" alt="John Doe" class="team-img">
            <div class="team-body">
              <h4 class="team-name">John Doe</h4>
              <p class="team-position">Founder & CEO</p>
              <p>With over 15 years of experience in child welfare, John founded Tender Wings Home to make a lasting difference.</p>
              <div class="team-social">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-linkedin-in"></i></a>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="team-card">
            <img src="user.jpg" alt="Jane Smith" class="team-img">
            <div class="team-body">
              <h4 class="team-name">Jane Smith</h4>
              <p class="team-position">Head of Caregiving</p>
              <p>Jane brings compassion and expertise to ensure every child receives the highest quality care and support.</p>
              <div class="team-social">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-linkedin-in"></i></a>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="team-card">
            <img src="user2.jpg" alt="Mike Johnson" class="team-img">
            <div class="team-body">
              <h4 class="team-name">Mike Johnson</h4>
              <p class="team-position">Volunteer Coordinator</p>
              <p>Mike connects compassionate individuals with opportunities to make a direct impact in our children's lives.</p>
              <div class="team-social">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-linkedin-in"></i></a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section id="programs" class="section programs-section">
    <div class="container">
      <h2 class="section-title">Our Programs</h2>
      <p class="section-subtitle">Comprehensive services designed to meet the physical, emotional, and educational needs of every child</p>
      
      <div class="row">
        <div class="col-md-6 col-lg-3 mb-4">
          <div class="program-card">
            <i class="fas fa-book program-icon"></i>
            <h3>Education & Learning</h3>
            <p>We provide scholarships, tutoring programs, and school partnerships to ensure every child has access to quality education.</p>
          </div>
        </div>
        <div class="col-md-6 col-lg-3 mb-4">
          <div class="program-card">
            <i class="fas fa-heartbeat program-icon"></i>
            <h3>Health & Well-being</h3>
            <p>Comprehensive medical care, nutrition programs, and mental health support to nurture healthy development.</p>
          </div>
        </div>
        <div class="col-md-6 col-lg-3 mb-4">
          <div class="program-card">
            <i class="fas fa-hands-helping program-icon"></i>
            <h3>Counseling & Mentorship</h3>
            <p>Emotional and spiritual growth through professional counseling and mentorship programs with caring adults.</p>
          </div>
        </div>
        <div class="col-md-6 col-lg-3 mb-4">
          <div class="program-card">
            <i class="fas fa-paint-brush program-icon"></i>
            <h3>Recreation & Skills</h3>
            <p>Arts, music, sports, and vocational training programs to help children discover and develop their talents.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="stats-section section">
    <div class="container">
      <h2 class="section-title" style="color: white;">Our Impact in Numbers</h2>
      
      <div class="row">
        <div class="col-md-3">
          <div class="stat-item">
            <i class="fas fa-child stat-icon"></i>
            <div class="stat-number">500+</div>
            <div class="stat-label">Children Helped</div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="stat-item">
            <i class="fas fa-school stat-icon"></i>
            <div class="stat-number">200+</div>
            <div class="stat-label">Schools Supported</div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="stat-item">
            <i class="fas fa-hand-holding-heart stat-icon"></i>
            <div class="stat-number">100+</div>
            <div class="stat-label">Volunteers</div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="stat-item">
            <i class="fas fa-heartbeat stat-icon"></i>
            <div class="stat-number">300+</div>
            <div class="stat-label">Health Checkups</div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section id="get-involved" class="section">
    <div class="container">
      <h2 class="section-title">Get Involved</h2>
      <p class="section-subtitle">Join us in making a difference in children's lives through various opportunities</p>
      
      <div class="row">
        <div class="col-md-4 mb-4">
          <div class="program-card">
            <i class="fas fa-donate program-icon"></i>
            <h3>Donate</h3>
            <p>Your financial support helps us provide food, shelter, education, and medical care to children in need.</p>
            <a href="about.php" class="btn btn-primary">Donate Now</a>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="program-card">
            <i class="fas fa-hands-helping program-icon"></i>
            <h3>Volunteer</h3>
            <p>Share your time and skills to mentor, teach, or help with daily operations at our facility.</p>
            <a href="login.php" class="btn btn-success">Volunteer Now</a>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="program-card">
            <i class="fas fa-calendar-alt program-icon"></i>
            <h3>Events</h3>
            <p>Participate in our fundraising events, awareness campaigns, and community outreach programs.</p>
            <a href="#" class="btn btn-warning">View Events</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section id="gallery" class="section gallery-section">
    <div class="container">
      <h2 class="section-title">Our Gallery</h2>
      <p class="section-subtitle">Moments of joy, learning, and transformation at Tender Wings Home</p>
      
      <div class="row">
        <div class="col-md-4 col-sm-6">
          <div class="gallery-item">
            <img src="image1.jpg" alt="Gallery Image 1" class="gallery-img">
            <div class="gallery-overlay">
              <h5>Classroom Learning</h5>
            </div>
          </div>
        </div>
        <div class="col-md-4 col-sm-6">
          <div class="gallery-item">
            <img src="image2.jpg" alt="Gallery Image 2" class="gallery-img">
            <div class="gallery-overlay">
              <h5>Play Time</h5>
            </div>
          </div>
        </div>
        <div class="col-md-4 col-sm-6">
          <div class="gallery-item">
            <img src="image3.jpg" alt="Gallery Image 3" class="gallery-img">
            <div class="gallery-overlay">
              <h5>Art Workshop</h5>
            </div>
          </div>
        </div>
        <div class="col-md-4 col-sm-6">
          <div class="gallery-item">
            <img src="image4.jpg" alt="Gallery Image 4" class="gallery-img">
            <div class="gallery-overlay">
              <h5>Medical Checkup</h5>
            </div>
          </div>
        </div>
        <div class="col-md-4 col-sm-6">
          <div class="gallery-item">
            <img src="image5.jpg" alt="Gallery Image 5" class="gallery-img">
            <div class="gallery-overlay">
              <h5>Graduation Day</h5>
            </div>
          </div>
        </div>
        <div class="col-md-4 col-sm-6">
          <div class="gallery-item">
            <img src="image6.jpg" alt="Gallery Image 6" class="gallery-img">
            <div class="gallery-overlay">
              <h5>Community Event</h5>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section id="testimonials" class="section">
    <div class="container">
      <h2 class="section-title">What People Say</h2>
      <p class="section-subtitle">Hear from those whose lives we've touched</p>
      
      <div class="row">
        <div class="col-md-4 mb-4">
          <div class="testimonial-card">
            <div class="testimonial-text">
              Tender Wings Home has been a blessing to our community. Their dedication to children is unmatched. My niece found a new family here when she had nowhere else to go.
            </div>
            <div class="testimonial-author">
              <img src="user.jpg" alt="John Doe" class="testimonial-img">
              <div>
                <div class="testimonial-name">John Doe</div>
                <div class="testimonial-role">Community Leader</div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="testimonial-card">
            <div class="testimonial-text">
              I volunteered with Tender Wings Home, and it was one of the most rewarding experiences of my life. The staff's commitment to these children is truly inspiring.
            </div>
            <div class="testimonial-author">
              <img src="user.jpg" alt="Jane Smith" class="testimonial-img">
              <div>
                <div class="testimonial-name">Jane Smith</div>
                <div class="testimonial-role">Volunteer</div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="testimonial-card">
            <div class="testimonial-text">
              The programs here are life-changing. I'm proud to support such an amazing organization that's making a real difference in children's lives every single day.
            </div>
            <div class="testimonial-author">
              <img src="user.jpg" alt="Mike Johnson" class="testimonial-img">
              <div>
                <div class="testimonial-name">Mike Johnson</div>
                <div class="testimonial-role">Donor</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section id="contact" class="section contact-section">
    <div class="container">
      <h2 class="section-title">Contact Us</h2>
      <p class="section-subtitle">We'd love to hear from you. Reach out for inquiries, partnerships, or support.</p>
      
      <div class="row">
        <div class="col-lg-6 mb-4">
          <div class="contact-card">
            <h3 class="footer-title">Get In Touch</h3>
            
            <div class="contact-info">
              <div class="contact-item">
                <i class="fas fa-map-marker-alt contact-icon"></i>
                <div>
                  <h5>Address</h5>
                  <p>Hope Street, Kiambu, Kenya</p>
                </div>
              </div>
              
              <div class="contact-item">
                <i class="fas fa-phone-alt contact-icon"></i>
                <div>
                  <h5>Phone</h5>
                  <p>+254729298735</p>
                </div>
              </div>
              
              <div class="contact-item">
                <i class="fas fa-envelope contact-icon"></i>
                <div>
                  <h5>Email</h5>
                  <p>info@tenderwingshome.org</p>
                </div>
              </div>
            </div>
            
            <h5>Follow Us</h5>
            <div class="social-links">
              <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
              <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
              <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
              <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>
              <a href="#" class="social-link"><i class="fab fa-youtube"></i></a>
            </div>
          </div>
        </div>
        
        <div class="col-lg-6 mb-4">
          <div class="contact-card">
            <h3 class="footer-title">Send Us a Message</h3>
            
            <form class="contact-form">
              <div class="form-group">
                <input type="text" class="form-control" placeholder="Your Name" required>
              </div>
              
              <div class="form-group">
                <input type="email" class="form-control" placeholder="Your Email" required>
              </div>
              
              <div class="form-group">
                <input type="text" class="form-control" placeholder="Subject">
              </div>
              
              <div class="form-group">
                <textarea class="form-control" rows="5" placeholder="Your Message" required></textarea>
              </div>
              
              <button type="submit" class="btn btn-primary">Send Message</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>

  <footer class="footer">
    <div class="container">
      <div class="row">
        <div class="col-lg-4 mb-4">
          <img src="logotw.png" alt="Tender Wings Home Logo" class="footer-logo">
          <div class="footer-about">
            <p>Tender Wings Home is a non-profit organization dedicated to providing shelter, education, and care to children in need.</p>
          </div>
        </div>
        
        <div class="col-lg-2 col-md-6 mb-4">
          <h3 class="footer-title">Quick Links</h3>
          <ul class="footer-links">
            <li><a href="#home">Home</a></li>
            <li><a href="#about">About Us</a></li>
            <li><a href="#programs">Programs</a></li>
            <li><a href="#get-involved">Get Involved</a></li>
            <li><a href="#contact">Contact</a></li>
          </ul>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-4">
          <h3 class="footer-title">Our Programs</h3>
          <ul class="footer-links">
            <li><a href="#">Education</a></li>
            <li><a href="#">Healthcare</a></li>
            <li><a href="#">Counseling</a></li>
            <li><a href="#">Vocational Training</a></li>
            <li><a href="#">Recreation</a></li>
          </ul>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-4">
          <h3 class="footer-title">Legal</h3>
          <ul class="footer-links">
            <li><a href="#">Privacy Policy</a></li>
            <li><a href="#">Terms of Service</a></li>
            <li><a href="#">Financial Reports</a></li>
            <li><a href="#">Transparency</a></li>
          </ul>
        </div>
      </div>
      
      <div class="footer-bottom">
        <p>&copy; 2025 Tender Wings Home. All rights reserved. Registered Charity No. 123456</p>
      </div>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <script>
   
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    
    sidebarToggle.addEventListener('click', () => 
    {
      sidebar.classList.toggle('active');
    });
    
  
    document.addEventListener('click', (event) => 
    {
      if (!sidebar.contains(event.target) && !sidebarToggle.contains(event.target)) 
      {
        sidebar.classList.remove('active');
      }
    });
    
   
    document.querySelectorAll('a[href^="#"]').forEach(anchor => 
    {
      anchor.addEventListener('click', function(e) 
      {
        e.preventDefault();
        
        sidebar.classList.remove('active');
        
        const targetId = this.getAttribute('href');
        if (targetId === '#') return;
        
        const targetElement = document.querySelector(targetId);
        if (targetElement) {
          window.scrollTo({
            top: targetElement.offsetTop - 80,
            behavior: 'smooth'
          });
        }
      });
    });
    
    const animateOnScroll = () => 
    {
      const elements = document.querySelectorAll('.program-card, .team-card, .testimonial-card, .gallery-item');
      
      elements.forEach(element => {
        const elementPosition = element.getBoundingClientRect().top;
        const screenPosition = window.innerHeight / 1.2;
        
        if (elementPosition < screenPosition) 
        {
          element.style.opacity = '1';
          element.style.transform = 'translateY(0)';
        }
      });
    };

    document.querySelectorAll('.program-card, .team-card, .testimonial-card, .gallery-item').forEach(element => {
      element.style.opacity = '0';
      element.style.transform = 'translateY(20px)';
      element.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
    });

    window.addEventListener('scroll', animateOnScroll);
    window.addEventListener('load', animateOnScroll);
  </script>
</body>
</html>
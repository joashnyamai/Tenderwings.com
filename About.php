<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About Us | Tender Wings Home</title>
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
    
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    
    body 
    {
      font-family: 'Poppins', sans-serif;
      background-color: var(--light);
      color: var(--dark);
      line-height: 1.6;
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
    
    .sidebar-header 
    {
      text-align: center;
      margin-bottom: 2rem;
    }
    
    .sidebar-logo 
    {
      width: 120px;
      height: auto;
      margin-bottom: 1rem;
    }
    
    .sidebar-title 
    {
      font-family: 'Playfair Display', serif;
      font-weight: 700;
      font-size: 1.5rem;
      margin-bottom: 0.5rem;
    }
    
    .sidebar-menu 
    {
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
    
    .about-hero 
    {
      background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('image7.jpg') no-repeat center center/cover;
      height: 60vh;
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
    }
    
    .hero-title 
    {
      font-family: 'Playfair Display', serif;
      font-size: 3rem;
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
    
    .cta-button 
    {
      padding: 0.8rem 2rem;
      font-size: 1.1rem;
      font-weight: 500;
      border-radius: 50px;
      transition: var(--transition);
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      background: var(--warning);
      color: var(--dark);
      text-decoration: none;
    }
    
    .cta-button:hover 
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
      position: relative;
      overflow: hidden;
      z-index: 1;
    }
    
    .about-card::before 
    {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 5px;
      height: 100%;
      background: var(--primary);
      transition: var(--transition);
      z-index: -1;
    }
    
    .about-card:hover 
    {
      transform: translateY(-10px);
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
    }
    
    .about-card:hover::before 
    {
      width: 100%;
      opacity: 0.1;
    }
    
    .about-icon 
    {
      font-size: 2.5rem;
      color: var(--primary);
      margin-bottom: 1.5rem;
    }
    
    .values-section 
    {
      background: var(--primary-light);
    }
    
    .value-card 
    {
      background: white;
      border-radius: var(--border-radius);
      padding: 2rem;
      box-shadow: var(--box-shadow);
      height: 100%;
      text-align: center;
      transition: var(--transition);
    }
    
    .value-card:hover 
    {
      transform: translateY(-10px);
    }
    
    .value-icon 
    {
      width: 80px;
      height: 80px;
      background: var(--primary-light);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 1.5rem;
      font-size: 2rem;
      color: var(--primary);
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
    
    .team-social 
    {
      display: flex;
      justify-content: center;
      gap: 0.8rem;
    }
    
    .team-social a 
    {
      color: var(--gray);
      transition: var(--transition);
    }
    
    .team-social a:hover 
    {
      color: var(--primary);
    }
    
    .payment-section 
    {
      background: linear-gradient(135deg, var(--primary), var(--secondary));
      color: white;
    }
    
    .payment-card 
    {
      background: rgba(255, 255, 255, 0.1);
      border-radius: var(--border-radius);
      padding: 2rem;
      backdrop-filter: blur(5px);
      height: 100%;
    }
    
    .payment-icon 
    {
      font-size: 2.5rem;
      margin-bottom: 1.5rem;
      color: white;
    }
    
    .payment-details 
    {
      list-style: none;
      padding: 0;
    }
    
    .payment-details li 
    {
      margin-bottom: 0.8rem;
      display: flex;
      align-items: flex-start;
    }
    
    .payment-details i 
    {
      margin-right: 0.8rem;
      margin-top: 0.2rem;
    }
    
    .feature-item 
    {
      display: flex;
      align-items: flex-start;
      margin-bottom: 1.5rem;
    }
    
    .feature-icon 
    {
      font-size: 1.5rem;
      color: var(--primary);
      margin-right: 1rem;
      margin-top: 0.2rem;
    }
    
    .footer 
    {
      background: var(--dark);
      color: white;
      padding: 3rem 0 1rem;
    }
    
    .footer-logo 
    {
      width: 150px;
      margin-bottom: 1.5rem;
    }
    
    .footer-about 
    {
      margin-bottom: 1.5rem;
    }
    
    .footer-title 
    {
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
    
    .footer-links 
    {
      list-style: none;
      padding: 0;
    }
    
    .footer-links li 
    {
      margin-bottom: 0.8rem;
    }
    
    .footer-links a 
    {
      color: rgba(255, 255, 255, 0.7);
      text-decoration: none;
      transition: var(--transition);
      display: inline-block;
    }
    
    .footer-links a:hover 
    {
      color: white;
      transform: translateX(5px);
    }
    
    .footer-bottom 
    {
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
      to 
      {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    /* Responsive Styles */
    @media (max-width: 992px) 
    {
      .hero-title 
      {
        font-size: 2.5rem;
      }
      
      .section-title 
      {
        font-size: 2.2rem;
      }
    }
    
    @media (max-width: 768px) 
    {
      .hero-title 
      {
        font-size: 2rem;
      }
      
      .hero-subtitle 
      {
        font-size: 1rem;
      }
      
      .section-title 
      {
        font-size: 2rem;
      }
      
      .sidebar 
      {
        width: 250px;
      }
    }
    
    @media (max-width: 576px) 
    {
      .hero-title 
      {
        font-size: 1.8rem;
      }
      
      .section 
      {
        padding: 3rem 0;
      }
      
      .section-title 
      {
        font-size: 1.8rem;
        margin-bottom: 2rem;
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
      <a href="index.php" class="sidebar-link">
        <i class="fas fa-home"></i>
        <span>Home</span>
      </a>
      <a href="#about" class="sidebar-link">
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

  <section class="about-hero">
    <div class="hero-content">
      <h1 class="hero-title">Empowering Young Hearts, Creating Limitless Tomorrows</h1>
      <p class="hero-subtitle">Discover our mission, vision, and the passionate team behind Tender Wings Home</p>
      <a href="contact.php" class="cta-button">
        <i class="fas fa-heart"></i> Need More? Reach Out
      </a>
    </div>
  </section>

  <section id="about" class="section">
    <div class="container">
      <h2 class="section-title">Our Purpose</h2>
      <p class="section-subtitle">Guided by compassion and driven by purpose, we create a brighter future for children in need</p>
      
      <div class="row">
        <div class="col-lg-6 mb-4">
          <div class="about-card">
            <i class="fas fa-bullseye about-icon"></i>
            <h3>Our Mission</h3>
            <p>To provide a sanctuary of hope, love, and transformation for every child in need, ensuring they grow up in a safe, nurturing, and empowering environment. We are committed to addressing the physical, emotional, and educational needs of each child in our care.</p>
          </div>
        </div>
        <div class="col-lg-6 mb-4">
          <div class="about-card">
            <i class="fas fa-eye about-icon"></i>
            <h3>Our Vision</h3>
            <p>A world where every child, regardless of their background, has access to love, education, and opportunities to thrive and reach their full potential. We envision communities where all children are valued, protected, and given the tools to build successful futures.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="section" style="background-color: var(--light);">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6 mb-4">
          <h2 class="section-title text-start">Our Story</h2>
          <p>Founded in 2010, Tender Wings Home began as a small initiative to support vulnerable children in our community. What started as a modest shelter for five children has blossomed into a comprehensive care facility that has touched the lives of thousands.</p>
          <p>Our journey has been fueled by the unwavering support of our donors, volunteers, and partners who share our vision of a brighter future for every child. Each year, we expand our programs and reach, always guided by our core values of compassion, integrity, and empowerment.</p>
          <p>Today, we stand as a beacon of hope in our community, recognized for our innovative approaches to child welfare and our commitment to transparency and measurable impact.</p>
        </div>
        <div class="col-lg-6 mb-4">
          <img src="https://via.placeholder.com/600x400" alt="Our Story" class="img-fluid rounded shadow">
        </div>
      </div>
    </div>
  </section>

  <section class="section values-section">
    <div class="container">
      <h2 class="section-title">Our Core Values</h2>
      <p class="section-subtitle">The principles that guide everything we do</p>
      
      <div class="row">
        <div class="col-md-4 mb-4">
          <div class="value-card">
            <div class="value-icon">
              <i class="fas fa-heart"></i>
            </div>
            <h3>Compassion</h3>
            <p>We treat every child with unconditional love, kindness, and understanding, recognizing the unique challenges they face.</p>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="value-card">
            <div class="value-icon">
              <i class="fas fa-shield-alt"></i>
            </div>
            <h3>Integrity</h3>
            <p>We maintain the highest standards of transparency and accountability in all our actions and decisions.</p>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="value-card">
            <div class="value-icon">
              <i class="fas fa-hand-holding-heart"></i>
            </div>
            <h3>Empowerment</h3>
            <p>We equip children with the tools, education, and confidence they need to succeed in life and break cycles of poverty.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="section">
    <div class="container">
      <h2 class="section-title">Meet Our Team</h2>
      <p class="section-subtitle">The passionate individuals who make our mission possible</p>
      
      <div class="row">
        <div class="col-md-4 mb-4">
          <div class="team-card">
            <img src="logotw.png" alt="John Doe" class="team-img">
            <div class="team-body">
              <h4 class="team-name">John Doe</h4>
              <p class="team-position">Founder & CEO</p>
              <p>With over 15 years of experience in child welfare, John founded Tender Wings Home to make a lasting difference in children's lives.</p>
              <div class="team-social">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-linkedin-in"></i></a>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4 mb-4">
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
        <div class="col-md-4 mb-4">
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

  <section class="section payment-section">
    <div class="container">
      <h2 class="section-title" style="color: white;">Support Our Mission</h2>
      <p class="section-subtitle" style="color: rgba(255,255,255,0.8);">Your contributions make our work possible</p>
      
      <div class="row">
        <div class="col-lg-8 mx-auto">
          <div class="payment-card">
            <i class="fas fa-credit-card payment-icon"></i>
            <h3 style="color: white;">Payment Details</h3>
            <p style="margin-bottom: 1.5rem;">Your support makes a difference! Here's how you can contribute:</p>
            
            <ul class="payment-details">
              <li><i class="fas fa-university"></i> <strong>Bank Name:</strong> Tender Wings Bank</li>
              <li><i class="fas fa-signature"></i> <strong>Account Name:</strong> Tender Wings Home</li>
              <li><i class="fas fa-hashtag"></i> <strong>Account Number:</strong> 1234567890</li>
              <li><i class="fas fa-globe"></i> <strong>Swift Code:</strong> TWHS1234</li>
              <li><i class="fas fa-mobile-alt"></i> <strong>Mobile Payment:</strong> Paybill: 123456, Account: Tender Wings</li>
            </ul>
            
            <p style="margin-top: 1.5rem;">For more information about donations or partnerships, please <a href="contact.php" style="color: white; text-decoration: underline;">contact us</a>.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="section">
    <div class="container">
      <h2 class="section-title">Why Choose Tender Wings Home?</h2>
      <p class="section-subtitle">What sets us apart in our mission to serve children</p>
      
      <div class="row">
        <div class="col-lg-6 mb-4">
          <div class="feature-item">
            <i class="fas fa-check-circle feature-icon"></i>
            <div>
              <h4>Proven Impact</h4>
              <p>Over 500 children supported and counting, with measurable improvements in their lives and futures.</p>
            </div>
          </div>
          
          <div class="feature-item">
            <i class="fas fa-check-circle feature-icon"></i>
            <div>
              <h4>Transparency</h4>
              <p>Regular updates and reports on how your contributions are used to transform children's lives.</p>
            </div>
          </div>
        </div>
        
        <div class="col-lg-6 mb-4">
          <div class="feature-item">
            <i class="fas fa-check-circle feature-icon"></i>
            <div>
              <h4>Community Focus</h4>
              <p>We work closely with local communities to create sustainable, long-term change.</p>
            </div>
          </div>
          
          <div class="feature-item">
            <i class="fas fa-check-circle feature-icon"></i>
            <div>
              <h4>Passionate Team</h4>
              <p>Our dedicated staff and volunteers are committed to making a difference every single day.</p>
            </div>
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
            <li><a href="index.php">Home</a></li>
            <li><a href="#about">About Us</a></li>
            <li><a href="#programs">Programs</a></li>
            <li><a href="#get-involved">Get Involved</a></li>
            <li><a href="contact.php">Contact</a></li>
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
      if (!sidebar.contains(event.target) && !sidebarToggle.contains(event.target)) {
        sidebar.classList.remove('active');
      }
    });
    
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => 
    {
      anchor.addEventListener('click', function(e) 
      {
        e.preventDefault();
        
        // Close sidebar if open
        sidebar.classList.remove('active');
        
        const targetId = this.getAttribute('href');
        if (targetId === '#') return;
        
        const targetElement = document.querySelector(targetId);
        if (targetElement) 
        {
          window.scrollTo(
            {
            top: targetElement.offsetTop - 80,
            behavior: 'smooth'
          });
        }
      });
    });
    
    const animateOnScroll = () => 
    {
      const elements = document.querySelectorAll('.about-card, .value-card, .team-card');
      
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
    
    document.querySelectorAll('.about-card, .value-card, .team-card').forEach(element => 
    {
      element.style.opacity = '0';
      element.style.transform = 'translateY(20px)';
      element.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
    });
    window.addEventListener('scroll', animateOnScroll);
    window.addEventListener('load', animateOnScroll);
  </script>
</body>
</html>
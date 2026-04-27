<?php 
include 'header.php'; 
$success = isset($_GET['success']);
?>

        <section class="section active" id="contact">

            <div class="contact-hero">
                <div class="contact-hero-orb orb-a"></div>
                <div class="contact-hero-orb orb-b"></div>
                <div class="section-label">GET IN TOUCH</div>
                <h2 class="section-title" style="margin-top: 14px;">
                    We'd Love to <span class="gradient-text">Hear From You</span>
                </h2>
                <p class="section-description" style="max-width: 560px; margin: 12px auto 0;">
                    Have a question, suggestion, or just want to say hello? Our team typically responds within 2 hours.
                </p>
            </div>

            <div class="contact-layout">

                <!-- Info column -->
                <div class="contact-info-col">

                    <div class="contact-info-card" style="--card-delay: 0.1s;">
                        <div class="contact-info-icon" style="background: linear-gradient(135deg, #0052CC, #0066FF)"><i class="fas fa-phone-alt"></i></div>
                        <div class="contact-info-text">
                            <span class="contact-info-label">Call Us</span>
                            <span class="contact-info-value">03180305588</span>
                            <span class="contact-info-sub">Mon-Sat, 9am - 7pm IST</span>
                        </div>
                    </div>

                    <div class="contact-info-card" style="--card-delay: 0.2s;">
                        <div class="contact-info-icon" style="background: linear-gradient(135deg, #059669, #10b981)"><i class="fas fa-envelope"></i></div>
                        <div class="contact-info-text">
                            <span class="contact-info-label">Email Us</span>
                            <span class="contact-info-value">support@hostelhub.com</span>
                            <span class="contact-info-sub">We reply within 2 hours</span>
                        </div>
                    </div>

                    <div class="contact-info-card" style="--card-delay: 0.3s;">
                        <div class="contact-info-icon" style="background: linear-gradient(135deg, #d97706, #f59e0b)"><i class="fas fa-map-marker-alt"></i></div>
                        <div class="contact-info-text">
                            <span class="contact-info-label">Visit Us</span>
                            <span class="contact-info-value">Qasimabad Hyderabad</span>
                            <span class="contact-info-sub">Open for walk-ins Mon-Fri</span>
                        </div>
                    </div>

                    <div class="contact-info-card" style="--card-delay: 0.4s;">
                        <div class="contact-info-icon" style="background: linear-gradient(135deg, #7c3aed, #a855f7)"><i class="fas fa-clock"></i></div>
                        <div class="contact-info-text">
                            <span class="contact-info-label">Office Hours</span>
                            <span class="contact-info-value">Mon - Sat: 9am - 7pm</span>
                            <span class="contact-info-sub">Online support: 24/7</span>
                        </div>
                    </div>

                    <div class="contact-social-box">
                        <p class="contact-social-label">Connect on Social</p>
                        <div class="contact-social-icons">
                            
                        <a style = "text-decoration:none" href="https://www.facebook.com/profile.php?id=61574825833558" target="_blank">
                            <div class="c-social-icon"><i class="fab fa-facebook-f"></i></div>
                        </a>    

                            <div class="c-social-icon"><i class="fab fa-twitter"></i></div>

                        <a style = "text-decoration:none" href="https://www.instagram.com/aarish_ali_memon/" target="_blank">
                            <div class="c-social-icon"><i class="fab fa-instagram"></i></div>
                        </a>
                        <a style = "text-decoration:none" href="https://www.linkedin.com/in/aarish-ali-a20704367" target="_blank">
                                <div class="c-social-icon"><i class="fab fa-linkedin-in"></i></div>
                        </a>
                        </div>
                    </div>
                </div>

                <!-- Form column -->
                <div class="contact-form-col">
                    <div class="contact-form-card">
                        <div class="contact-form-header">
                            <h3 class="contact-form-title">Send Us a Message</h3>
                            <p class="contact-form-sub">Fill in the details below and we'll get back to you shortly.</p>
                        </div>

                        <?php if ($success): ?>
                            <div class="contact-success" style="display:flex;">
                                <div class="contact-success-icon"><i class="fas fa-check"></i></div>
                                <h4>Message Sent!</h4>
                                <p>Thanks for reaching out. We'll get back to you within 2 hours.</p>
                                <a href="contact.php" class="contact-reset-btn" style="text-decoration: none;">Send Another Message</a>
                            </div>
                        <?php else: ?>
                            <form method="POST" action="../Backend/backend.php">
                                <div class="contact-form-row">
                                    <div class="contact-fg">
                                        <label class="contact-label">Full Name</label>
                                        <div class="contact-input-wrap">
                                            <span class="contact-inp-icon"><i class="fas fa-user"></i></span>
                                            <input type="text" class="contact-input" name="c_name" placeholder="John Doe" required>
                                        </div>
                                    </div>
                                    <div class="contact-fg">
                                        <label class="contact-label">Email Address</label>
                                        <div class="contact-input-wrap">
                                            <span class="contact-inp-icon"><i class="fas fa-envelope"></i></span>
                                            <input type="email" class="contact-input" name="c_email" placeholder="you@example.com" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="contact-fg" style="margin-bottom: 22px;">
                                    <label class="contact-label">Phone Number (Optional)</label>
                                    <div class="contact-input-wrap">
                                        <span class="contact-inp-icon"><i class="fas fa-phone-alt"></i></span>
                                        <input type="tel" class="contact-input" name="c_phone" placeholder="+91 98765 43210">
                                    </div>
                                </div>
                                <div class="contact-fg" style="margin-bottom: 22px;">
                                    <label class="contact-label">Subject</label>
                                    <div class="contact-input-wrap">
                                        <span class="contact-inp-icon"><i class="fas fa-tag"></i></span>
                                        <select class="contact-input contact-select" name="c_subject">
                                            <option value="" disabled selected>Select a subject…</option>
                                            <option value="booking">Hostel Booking Query</option>
                                            <option value="complaint">Complaint / Issue</option>
                                            <option value="partnership">Partnership / Listing</option>
                                            <option value="support">Technical Support</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="contact-fg" style="margin-bottom: 28px;">
                                    <label class="contact-label">Your Message</label>
                                    <textarea class="contact-input contact-textarea" name="c_message" placeholder="Tell us how we can help you…" required></textarea>
                                </div>
                                <button type="submit" name="contact_sub_btn" class="contact-submit-btn">
                                    <span class="contact-btn-text">Send Message</span>
                                    <span class="contact-btn-icon"><i class="fas fa-paper-plane"></i></span>
                                </button>
                            </form>
                        <?php endif; ?>

                    </div>

                    <div class="contact-faq-strip">
                        <div class="faq-strip-header">
                            <i class="fas fa-lightbulb"></i>
                            <span>Frequently Asked Questions</span>
                        </div>
                        <div class="faq-items">
                            <div class="faq-item">
                                <button class="faq-q" onclick="toggleFaq(this)">
                                    Is HostelHub free to use?
                                    <i class="fas fa-plus faq-icon"></i>
                                </button>
                                <div class="faq-a">Yes! Signing up and browsing hostels is completely free. No hidden charges ever.</div>
                            </div>
                            <div class="faq-item">
                                <button class="faq-q" onclick="toggleFaq(this)">
                                    How long does application approval take?
                                    <i class="fas fa-plus faq-icon"></i>
                                </button>
                                <div class="faq-a">Most applications are reviewed and confirmed within 24-48 hours by the hostel management.</div>
                            </div>
                            <div class="faq-item">
                                <button class="faq-q" onclick="toggleFaq(this)">
                                    Can I list my hostel on HostelHub?
                                    <i class="fas fa-plus faq-icon"></i>
                                </button>
                                <div class="faq-a">Absolutely! Contact us via the form above and our partnership team will onboard you within 48 hours.</div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </section>

<?php include 'footer.php'; ?>
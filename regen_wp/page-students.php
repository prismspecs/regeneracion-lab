<?php
/**
 * Template Name: Students Page
 * The template for displaying the Students page.
 */

get_header();

if ( have_posts() ) :
    while ( have_posts() ) : the_post(); ?>
        <div class="fade-in">
            <div class="article-header article-header-compact">
                <h1 class="article-title"><?php the_title(); ?></h1>
            </div>

            <section class="section">
                <h2 class="section-header section-header-sans">Reading Groups & Study Circles</h2>
                
                <!-- Active Group -->
                <div class="project-card full-width mb-30">
                    <div class="item-badge">ACTIVE</div>
                    <h3 class="mt-10">2025-2026; Critical Temporalities Reading Group</h3>
                    <p class="mt-15">Join the Regeneración Lab and the UCSB English Department Literature & Environment research center for an interdisciplinary reading group on time, space, and environment. This collaborative space brings together students, faculty, and community members to engage with critical scholarship on temporality and environmental justice.</p>
                    <a href="#join-form" class="btn mt-20">→ JOIN THE GROUP</a>
                </div>

                <!-- New Group -->
                <div class="project-card full-width">
                    <h3 class="mt-0">Winter 2026; Python Learning Lab / Coding for Justice Collaboration Space</h3>
                    <p class="mt-15">Join the Regeneración Lab and the Transcriptions Center for Digital Humanities in a collaborative space for learning and developing projects in Python. Open to students, faculty, and community members interested in learning how to code in Python or who want to workshop coding Digital Humanities and Research Justice frameworks projects. Facilitated by B.T. Werner.</p>
                    <a href="#join-form" class="btn mt-20">→ JOIN THE LAB</a>
                </div>
            </section>

            <section class="section">
                <h2 class="section-header section-header-sans">Study & Research Opportunities</h2>
                <div class="article-content">
                    <?php the_content(); ?>
                </div>
            </section>

            <!-- Shared Form -->
            <section class="section form-section" id="join-form">
                <h2 class="section-header section-header-sans">Join a Reading Group / Study Circle</h2>
                <form class="contact-form" action="https://formspree.io/f/YOUR_FORM_ID" method="POST" style="max-width: 800px;">
                    <div class="form-floating">
                        <input type="text" id="join-name" name="name" class="form-input" placeholder=" " required>
                        <label for="join-name" class="form-label">Name</label>
                    </div>
                    <div class="form-floating">
                        <input type="email" id="join-email" name="email" class="form-input" placeholder=" " required>
                        <label for="join-email" class="form-label">Email</label>
                    </div>
                    <div class="form-floating">
                        <input type="text" id="join-affiliation" name="affiliation" class="form-input" placeholder=" ">
                        <label for="join-affiliation" class="form-label">UCSB Affiliation</label>
                    </div>
                    <div class="form-floating">
                        <input type="text" id="join-department" name="department" class="form-input" placeholder=" ">
                        <label for="join-department" class="form-label">Major or Department</label>
                    </div>
                    <div class="form-group">
                        <label class="form-label form-label-static">Which group(s) are you interested in?</label>
                        <div class="checkbox-group">
                            <label class="checkbox-label">
                                <input type="checkbox" name="group_interest" value="Critical Temporalities"> Critical Temporalities Reading Group
                            </label>
                            <label class="checkbox-label">
                                <input type="checkbox" name="group_interest" value="Python Learning Lab"> Python Learning Lab / Coding for Justice
                            </label>
                        </div>
                    </div>
                    <div class="form-floating">
                        <textarea id="join-interest" name="interest" class="form-textarea" placeholder=" " required rows="4"></textarea>
                        <label for="join-interest" class="form-label">Briefly describe your interest in the reading group / study circle</label>
                    </div>
                    <button type="submit" class="btn">→ SUBMIT INTEREST</button>
                </form>
                <p class="form-footer-note">Note: To enable form submission, configure Formspree to send to asalomon@ucsb.edu</p>
            </section>
        </div>
    <?php endwhile;
endif;

get_footer();

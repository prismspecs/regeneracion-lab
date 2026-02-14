<?php
/**
 * Template Name: Students Page
 * The template for displaying the Students page.
 */

get_header();

if (have_posts()):
    while (have_posts()):
        the_post(); ?>
        <div class="fade-in">
            <div class="article-header">
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

            <!-- TODO: Replace with Contact Form 7 shortcode if preferred -->
            <section class="section form-section" id="join-form">
                <h2 class="section-header section-header-sans">Join a Reading Group / Study Circle</h2>
                <?php echo do_shortcode('[contact-form-7 id="83" title="Students Join Form"]'); ?>
            </section>
        </div>
    <?php
    endwhile;
endif;

get_footer();
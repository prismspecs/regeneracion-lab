                </div><!-- #mainContent -->
            </div><!-- .content-wrapper -->

            <footer class="site-footer">
                <p>&copy; <span id="currentYear"></span> <?php bloginfo( 'name' ); ?>. All rights reserved.</p>
            </footer>
        </div><!-- .mockup -->
    </div><!-- .app-container -->

    <script>
        // Set current year in footer
        document.getElementById('currentYear').textContent = new Date().getFullYear();
    </script>
    <?php wp_footer(); ?>
</body>

</html>

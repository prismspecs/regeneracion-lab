                </div><!-- #mainContent -->
            </div><!-- .content-wrapper -->

            <footer class="site-footer">
                <p>&copy; <span id="currentYear"></span> <?php bloginfo( 'name' ); ?>. All rights reserved.</p>
                <p class="site-designer-credit">Website designed by <a href="https://hire.graysonearle.com" target="_blank" rel="noopener noreferrer">Grayson Earle</a></p>
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

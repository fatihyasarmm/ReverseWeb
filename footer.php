<?php
if(isset($link) && $link instanceof mysqli){
    mysqli_close($link);
}
?>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> ReverseWeb | A Vulnerable Web Application for Educational Purposes</p>
    </footer>
</body>
</html>
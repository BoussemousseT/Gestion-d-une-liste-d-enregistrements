<footer>
    Designed by <?= self::AUTHOR; ?> &copy;
    <br>
    <?= COMPANY_INFO['NAME']; ?>
    <br>
    <?= COMPANY_INFO['STREET_ADDRESS']; ?>
    <?= COMPANY_INFO['CITY']; ?>
    <?= COMPANY_INFO['PROVINCE']; ?>
    <?= COMPANY_INFO['COUNTRY']; ?>
    <?= COMPANY_INFO['POSTAL_CODE']; ?>
    <br>
    <?= COMPANY_INFO['TELEPHONE'] . " |"; ?>
    <a <?= COMPANY_INFO['EMAIL']; ?>> <?= COMPANY_INFO['EMAIL']; ?></a>
    <br>
    Views: <?= $this->countviews ?>
    <br>

</footer>
</div>
</body>

</html>
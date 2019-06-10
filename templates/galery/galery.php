<html>
{% include 'head.html' %}
<body>
<div class="container">
    {% include 'header.html' %}
    <div class="content">
        <div class="content_inside">
            <div class="menuProducts">
                {% include 'bread_crumbs.html' %}
                <div class="products">
                    {% include 'galery/photogalery.html' %}
                </div>
            </div>
            {% include 'brand.html' %}
            {% include 'instagram.html' %}

            <div class="sosial_big">
                <div class="facebook"><a href="https://www.facebook.com/?ref=tn_tnmn">
                        <i class="fab fa-facebook-f"></i></a></div>
                <div class="twitter"><a href="https://twitter.com/login?lang=ru">
                        <i class="fab fa-twitter"></i></a></div>
                <div class="printest"><a href="https://www.pinterest.com/">
                        <i class="fab fa-pinterest-p"></i></a></div>
            </div>
        </div>
    </div>
</div>
{% include 'footer.html' %}

</body>
</html>
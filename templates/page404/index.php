{% extends 'index.html' %}
    {% block content %}
        <h1 class="text_bold_italic">Запрашиваемая вами таблица отстутствует или удалена. Воспользуйтесь поиском по сайту.</h1>
        {% include 'new-product.html' %}
        {% include 'top-product.html' %}
        {% include 'sale-product.html' %}
    {% endblock %}


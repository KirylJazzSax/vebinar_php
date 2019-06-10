{% extends 'index.html' %}
    {% block content %}
        {% include 'bread.html' %}
            {% if content_data.product %}
                <h1 class="text_bold_italic">Изменить товар!</h1>
                    {% else %}
                <h1 class="text_bold_italic">Добавить новый товар!</h1>
            {% endif %}
        {% for product in content_data.product %}
            {% include 'add_product.html' %}
        {% else %}
            {% include 'add_product.html' %}
        {% endfor %}
    {% endblock %}
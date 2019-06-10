{% extends 'index.html'%}
{% block before_content %}
    {% if content_data %}
            <div class="new_products">Ваша корзина</div>
        {% else %}
            <div class="new_products">Ваша корзина пуста</div>
    {% endif %}
{% endblock %}
    {% block content %}
        <div class="insert_basket">
            {% include 'basket.html' %}
        </div>
    {% endblock %}
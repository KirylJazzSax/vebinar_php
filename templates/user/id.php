{% extends 'index.html' %}
{% block before_content %}
    {% if content_data %}
        <div class="new_products insert"><a href="">Козина пользователя {{ content_data|first|last }}</a></div>
            {% else %}
        <div class="new_products insert"><a href="">Козина пользователя пуста</a></div>
    {% endif %}
{% endblock %}
{% block content %}
    <div class="insert_basket">
        {% include 'basket.html' %}
    </div>
{% endblock %}
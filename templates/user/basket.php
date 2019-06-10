{% extends 'index.html' %}
{% block before_content %}
    {% if content_data is empty %}
        <div class="new_products">Нету пользователей с корзинами!</div>
    {% endif %}
{% endblock %}
{% block content %}
    {% use 'users.html' %}
{% if user %}
    {% block link %}
        <p class="text"><a href="/user/basket/{{user.id_user}}" class="link">Поменять корзину пользователя?</a></p>
    {% endblock %}
{% endif %}
    {{ block('user') }}
{% endblock %}
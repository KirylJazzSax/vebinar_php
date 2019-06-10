{% extends 'index.html' %}
    {% block content %}
        {% use 'user_info.html' %}
        {{ block('user_info')}}
        {% if user %}
            {% block link %}
                <p><a href="{{ domain }}/{{ page }}/edit" class="link">Поменяем ваши личные данные?</a></p>
            {% endblock %}
        {% endif %}
    {% endblock %}

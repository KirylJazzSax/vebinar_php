{% extends 'index.html' %}
    {% block before_content %}
        <h1 class="text_bold_italic">Изменим данные</h1>
    {% endblock %}
    {% block content %}
        {% for user in content_data %}
            {% include 'edit_user.html' %}
        {% endfor %}
    {% endblock %}
{% extends 'index.html' %}
{% block content %}
    {% if role_info == 'editor' %}
        <div class="new_products">Здравствуйте! {{ isAuth.name }}</div>
        <p class="text">Здесь статьи добавленные и отредактированные вами!</p>
        <p class="text"><a href="{{ domain }}/{{ page }}/add" class="link">Добавить статью?</a></p>
    {% endif %}
    {% if role_info == 'admin' %}
        <div class="new_products">Здравствуйте! {{ isAuth.name }}</div>
        <p class="text">Здесь все статьи от всех редакторов и вы их можете все редактровать, добавлять или убирать!</p>
    {% endif %}
        {% include 'articles.html' %}
{% endblock %}
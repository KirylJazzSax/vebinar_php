{% extends 'index.html' %}
{% block content %}
    {% set change %}
        <p><input type="submit" name="edit_article" value="Именить статью"></p>
    {% endset %}
    {% set add %}
        <p><input type="submit" name="add_article" value="Добавить статью"></p>
    {% endset %}

<h1 class="text_bold_italic">{{ content_data ? 'Изменим статью!' : 'Добавить статью!' }}</h1>

<p class="text"><a href="{{ domain }}/{{ page }}"  class="link">Перейти к статьям?</a></p>
    {% for article in content_data %}
        {% include 'add_article.html' %}
            {% else %}
        {% include 'add_article.html' %}
    {% endfor %}
{% endblock %}
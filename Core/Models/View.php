<?php
class View {
    public function render($template,$data=[])
    {
        require Theme_Dir.'header.html';
        // Формирование пути к файлу шаблона
        $templatePath = Theme_Dir . $template . '.html';
        // Проверка наличия файла шаблона
        if (file_exists($templatePath)) {
            // Импорт переменных из массива данных
            extract($data);
            // Включение файла шаблона
            require $templatePath;
        } else {
            echo 'Ошибка: файл шаблона не найден.';
        }
        require Theme_Dir.'footer.html';
    }
}
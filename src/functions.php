<?php
function html_escape($var): string {
    return htmlspecialchars($var ?? '', ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
}

function redirect(string $url): void {
    header("Location: $url");
    exit();
}

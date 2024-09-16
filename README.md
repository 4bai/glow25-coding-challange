### glow25-coding-challange
## Tech-Team
# Testaufgabe / Full-Stack-Developer

Es gibt 2 WooCommerce-Shops, Shop A und Shop B, die auf separaten Servern laufen.
Die Benutzer sind in beiden Shops und Datenbanken völlig identisch, aber die Bestellungen
sind unterschiedlich.
Beide Shops enthalten große Datenmengen.

Erforderliche Frage: Du musst Bestellungen aus dem Jahr 2020 von Shop B den
eingeloggten Kunden im Frontend von Shop A anzeigen.
Eingeloggte Benutzer können nur ihre eigenen Bestellungen sehen.
Wie würdest du dies umsetzen, ohne neue Daten in die Datenbank von Shop A
hinzuzufügen?
> Ich würde die Bestellungen mit der WP/WooCommerce REST API aus dem Shop B laden.
```php
wp_remote_get('https://shop-b-url.com/wp-json/wc/v3/orders')
```
> Mit Parametern könnte dann festgelegt werden was geladen werden sollen:
```php
    $user_id = get_current_user_id();
    $args = [
        'customer' => $user_id, // Filter by user ID
        'after' => '2020-01-01T00:00:00', // Start of the year 2020
        'before' => '2020-12-31T23:59:59', // End of the year 2020
        'status' => 'any', // Optional: Filter by status, adjust as needed
        'page' => 1,
        'limit' => 20,
        '_fields' => 'id,date_created,total,currency,status', // Specify the fields needed
        'order' => 'desc'
    ];
```

Bonusfrage 1: Diese Bestellungen (Jahr 2020 aus Shop B) müssen auf neuen
Elementor-Seiten im Shop A angezeigt werden. Eine Elementor-Seite soll die Bestell-IDs pro
Benutzer auflisten, und eine Seite soll die Details einer bestimmten Bestellung anzeigen.
Wie würdest du dies umsetzen?
> Ich würde zwei Elementor Widgets erstellen. Eins für die Darstellung der Bestellungen in einer Liste. Das zweite um die Details zur Bestellung anzuzeigen.
```php
class Elementor_External_Orders_Widget extends \Elementor\Widget_Base
```

Bonusfrage 2: Shop B hat nur wenige Server-Ressourcen und kann keine belastenden
laufenden Datenbankabfragen verarbeiten. Wie lässt sich diese Aufgabe umsetzen, ohne
zusätzliche Server-Ressourcen für den Server von Shop B bereitzustellen und dabei so
wenig wie möglich oder keine zusätzlichen Daten in die Datenbank von Shop A einzufügen?
> Der load könnte durch das Cachen der API reduziert werden. Außerdem sollten nur die Daten angefragt werden die auch benötigt werden. zB in der Listenansicht: 
```php 
'_fields' => 'id,date_created,total,currency,status'
```
> Wenn es viele Bestellungen pro Kunden gibt kann auch durch Pagination der Load noch reduziert werden.

> Sollte das nicht reichen, können durch eine Datenbank Replikation auf einen anderen Server die Bestellungen per sql-query geladen werden. 
Es müssten nur die tabellen wp_posts und wp_meta repliziert werden.

Es reicht aus, nur die erforderliche Frage zu beantworten, aber wir wären beeindruckt, wenn
du auch Antworten auf die Bonusfragen liefern könntest.

Du kannst deine Antworten entweder nur als Text, als Kombination von Text und Code oder
ausschließlich als Code geben – je nachdem, was dir besser gefällt.
Bitte sende uns dein Feedback innerhalb von 3 Arbeitstagen.
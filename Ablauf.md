DDD - Workshop
==============

Buchverleih
-----------

1. Vorstellung der Domäne (Konzept + Business-Regeln)
2. Event Storming
    1. Events sammeln
    2. Commands definieren
    3. Entität definieren
    4. Thematische Gruppierung
    5. Bounded Context erkennen
3. Context Map???
4. BC Verleih erarbeiten (v1)
    1. Model für leihen und zurückgeben implementieren
        1. AR Buch hinzufügen
        2. Entity Ausleihe hinzufügen
5. BC Verkauf erarbeiten (v2)
    1. Wie kann der Verkauf darüber informiert werden, dass ein Buch 3 mal ausgeliehen wurde und nun zum Verkauf steht?
6. Policy Verleih/Verkauf hinzufügen (v3)
    1. Domain Event hinzufügen
    2. Event Handler hinzufügen
    3. Event dispatchen
7. Persistenz hinzufügen (v4)
        1. Doctrine per composer hinzufügen
        2. Composer require …
8. Event Sourcing hinzufügen (v5)
    1. Event Stream Table anlegen
    2. AggregateRoot mit Events versehen
    3. Repository anpassen

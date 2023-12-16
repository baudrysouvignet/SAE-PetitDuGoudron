<?php
/**
 * @param DatabaseManager $database
 * @return array
 */
function recoverAllEvents(
    DatabaseManager $database
): array {
    $query = $database->select(
        request: "SELECT * FROM Event ORDER BY Time ASC"
    );

    return $query;
}

/**
 * @param DatabaseManager $database
 * @param string $title
 * @param string $time
 * @param string $description
 * @param int $id
 * @return void
 */
function editEvent(
    DatabaseManager $database,
    string $title,
    string $time,
    string $description,
    int $id,
): void {
     $database->insert(
        request: "UPDATE Event SET Title = :title, Time = :time, Description = :description WHERE ID_Event = :id",
        param: [
            'title' => $title,
            'time' => $time,
            'description' => $description,
            'id' => $id,
        ]
    );
}

/**
 * @param DatabaseManager $database
 * @param string $title
 * @param string $time
 * @param string $description
 * @return void
 */
function addEvent(
    DatabaseManager $database,
    string $title,
    string $time,
    string $description,
): void {
    $database->insert(
        request: "INSERT INTO Event (Title, Time, Description) VALUES (:title, :time, :description)",
        param: [
            'title' => $title,
            'time' => $time,
            'description' => $description,
        ]
    );
}

/**
 * @param DatabaseManager $database
 * @param string $title
 * @param string $time
 * @param string $description
 * @return void
 *
 * Function qui ajoute ou modifie un event en fonction de si il existe deja ou non
 */
function addOrEditEvent(
    DatabaseManager $database,
    string $title,
    string $time,
    string $description
): void {
    //if un event existe deja a cette date, on le modifie sinon on le crée
    $query = $database->select(
        request: "SELECT * FROM Event WHERE Time = :time",
        param: [
            'time' => $time,
        ]
    );

    if (!empty($query)) {
       editEvent (
            database: $database,
            title: $title,
            time: $time,
            description: $description,
            id: $query[0]['ID_Event'],
        );
    } else {
        addEvent (
            database: $database,
            title: $title,
            time: $time,
            description: $description,
        );
    }

}

function deleteEvent(
    DatabaseManager $database,
    string $time
): void {
    $database->insert(
        request: "DELETE FROM Event WHERE Time = :time",
        param: [
            'time' => $time,
        ]
    );
}
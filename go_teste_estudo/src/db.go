package main

import (
    "database/sql"
    "fmt"
    "log"
	"strings"

    _ "github.com/go-sql-driver/mysql"
)

func ConnectToDB() (*sql.DB, error) {
    dsn := fmt.Sprintf("%s:%s@tcp(%s)/%s", DBUser, DBPassword, DBHost, DBName)
    db, err := sql.Open("mysql", dsn)
    if err != nil {
        return nil, err
    }
    return db, nil
}

func GetBoletosToEmail(db *sql.DB) ([]Boleto, error) {
    query := fmt.Sprintf("SELECT id, name FROM boletos WHERE generated = 'Y' AND sendMail = 'N' LIMIT %d", BatchSize)
    log.Printf("Executando query: %s", query)
    rows, err := db.Query(query)
    if err != nil {
        return nil, err
    }
    defer rows.Close()

    var boletos []Boleto
    for rows.Next() {
        var boleto Boleto
        if err := rows.Scan(&boleto.ID, &boleto.Name); err != nil {
            return nil, err
        }
        boletos = append(boletos, boleto)
    }
    return boletos, nil
}

func UpdateBoletosAsEmailed(db *sql.DB, boletoIDs []int) error {
    placeholders := make([]string, len(boletoIDs))
    args := make([]interface{}, len(boletoIDs))
    for i, id := range boletoIDs {
        placeholders[i] = "?"
        args[i] = id
    }
    placeholdersStr := strings.Join(placeholders, ",")
    query := fmt.Sprintf("UPDATE boletos SET sendMail = 'Y' WHERE id IN (%s)", placeholdersStr)
    _, err := db.Exec(query, args...)
    return err
}
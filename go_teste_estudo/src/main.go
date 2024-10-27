package main

import (
    "log"
    "time"
)

func main() {
    log.Println("Iniciando o listener de envio de emails.")

    for {
        db, err := ConnectToDB()
        if err != nil {
            log.Fatalf("Erro ao conectar ao banco de dados: %v", err)
        }

        boletos, err := GetBoletosToEmail(db)
        if err != nil {
            log.Printf("Erro ao buscar boletos: %v", err)
            db.Close()
            time.Sleep(time.Duration(CheckInterval) * time.Second)
            continue
        }

        if len(boletos) > 0 {
            boletoIDs := SendEmails(boletos)
            err = UpdateBoletosAsEmailed(db, boletoIDs)
            if err != nil {
                log.Printf("Erro ao atualizar boletos: %v", err)
            } else {
                log.Printf("Enviados %d emails e boletos atualizados.", len(boletoIDs))
            }
        } else {
            log.Println("Nenhum boleto encontrado para envio de e-mails.")
        }

        db.Close()
        time.Sleep(time.Duration(CheckInterval) * time.Second)
    }
}
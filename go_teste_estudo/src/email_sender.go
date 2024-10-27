package main

import "log"

func SendEmails(boletos []Boleto) []int {
    var boletoIDs []int
    for _, boleto := range boletos {
        log.Printf("Enviando email para boleto ID: %d, Nome: %s", boleto.ID, boleto.Name)

        boletoIDs = append(boletoIDs, boleto.ID)
    }
    return boletoIDs
}
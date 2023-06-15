#Histórico de Alterações

** v. 3.5.4 - 9 Jul 2021 **
- Opção de Download do boleto em PDF adicionado (sugerido por Frederico Marques de Castro @fredpiuma)
- Botões da tela de sucesso (ver boleto, download boleto e clique para realizar pagamento) agora seguem o padrão de botões do magento. Ao atualizar, veja se os botões estão 'ok' na sua tela de sucesso.

** v. 3.5.3 - 12 Mai 2021 **
- Pix adicionado aos meios de pagamento aceitos no pagamento com redirect (por Fábio da Diamix)

** v. 3.5.2 - 17 Mar 2021 **
- Suporte a atualização manual de pedidos
- Suporte a atualização automatica de pedidos (depende do modulo principal 3.11.x) 
- Correção de link de retentativa quebrado em ambiente Sandbox
- Correção em falha ao criar um pedido do tipo Kiosk em ambiente de Sandbox

** v. 3.5.1 - 28 Abr 2020**
- Correção em meio de pagamento "Redirecionar para o PagSeguro" que não funcionava em Magento < 1.9.1.0, gerando exceção na chamada do método queueNewOrderEmail. (Reportado por Anderson Aguiar) 

** v. 3.5.0 - 28 Abr 2020**
- Agora é possível escolher o URL de Redirect no checkout "Pagar com PagSeguro" (Redirect)
- Retentativa e Recuperação de pedidos deixa de ser beta

**v. 3.4.6 - 10 Abr 2020**
- Adicionado compatibilidade com IWD Checkout (requer modulo principal >= 3.8.2)

**v. 3.4.5 - 21 Nov 2019**
- Melhorias nos blocos que exibem informações dos pedidos feitos com Boleto e TEF no admin. 
  - Agora é possível clicar para ver o pedido no PagSeguro
  - Agora é possível ver as taxas cobradas pelo PagSeguro e o Total liquido a receber

**v. 3.4.4 - 30 Jul 2019**
- Corrigido suporte a Enviar para multiplos endereços. TEF, Redirect e Boleto eram exibidos para compras em múltiplos endereços mesmo sem haver suporte para tal funcionalidade. Agora não serão mais exibidos.


**v. 3.4.3 - 19 Jun 2019**
- Melhoria na tratativa das mensagens de erro e traduções
- Suporte a reembolso parcial para Boleto, Redirect e Tef. [Saiba mais.](https://github.com/r-martins/PagSeguro-Magento-Transparente/issues/250) 


**v. 3.4.2 - 08 Abr 2019**
- Pequena correção na exibição do Id da Transação e Link do boleto e TEF que eram exibidos sem os links quando o bloco de informação do pagamento era exibido no checkout (padrao Magento) antes da transação ser concluída.
- Acrescentado arquivo com histórico de alterações (este).


**v. 3.4.1 - 29 Mar 2019**
- Fix email transacional que não era enviado
- Modo redirecionar para PagSeguro agora é ativado por padrão


**v. 3.4.0 - 8 Fev 2019**
- Possibilidade de ser [redirecionado para pagar no PagSeguro](https://pagsegurotransparente.zendesk.com/hc/pt-br/sections/360003634151-Pagar-no-PagSeguro-Redirecionar-).

**v. 3.3.0 - 28 Set 2018**
- Possibilidade de retentativa de pagamento para pedidos negados. [Saiba mais](https://pagsegurotransparente.zendesk.com/hc/pt-br/sections/360000689312-Retentativa-e-Recupera%C3%A7%C3%A3o-de-Pedidos-beta-).
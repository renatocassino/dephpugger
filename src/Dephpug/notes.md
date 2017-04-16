##

Receive >>>>> Reflection Method (To get message parser) >>> Readline >>>>> Reflection Method (To get commands) >>> Readline | Repl

Receive >>>>> Receive Method (To get message parser) >>> Readline >>> Reflection Method (To get commands) >>> Send to DBGP >>> Receive | Repl

Receive >>> Reflection Method (To get message parser) >>> Readline >>> Reflection Method (To get commands) >>>> Send >>> Command >>> Readline | Repl

Commands precisa ter acesso aos plugins (de mensagem), pois no list eu irei pegar o fileprinter e executar passando novos parâmetros


# Logins de teste

Usuários criados pelo `seed.sql`. **A senha de todos é `123456`.**

No login dá para usar o nome de usuário ou o email.

Site: http://localhost:8080/Vitor%20Aires%20Diogenes/S.A-Charles/login.php

## Professores

| Usuário | Email | Turmas que administra |
|---|---|---|
| CaraRuim | cararuim@example.com | Turma do Charles, 3º Ano B - Mecatrônica |
| FernandaCosta | fernandacosta@example.com | 2º Ano A - Desenvolvimento de Sistemas, 3º Ano B - Mecatrônica, Robótica (Contraturno) |

## Alunos

| Usuário | Email | Turmas |
|---|---|---|
| CaraLegal | caralegal@example.com | Turma do Charles, 2º Ano A - Desenvolvimento de Sistemas |
| AnaSouza | anasouza@example.com | Turma do Charles, 3º Ano B - Mecatrônica |
| BrunoLima | brunolima@example.com | Turma do Charles, 3º Ano B - Mecatrônica |
| CarlaMendes | carlamendes@example.com | 2º Ano A - Desenvolvimento de Sistemas, 3º Ano B - Mecatrônica |
| DiegoRocha | diegorocha@example.com | 2º Ano A - Desenvolvimento de Sistemas, 3º Ano B - Mecatrônica |
| EduardaAlves | eduardaalves@example.com | nenhuma |

## Bons para testar

- **CaraLegal**: tem um projeto com prazo encerrado (Minha Calculadora) e propostas ainda abertas para criar projeto.
- **CarlaMendes**: tem um projeto ainda em rascunho (BiblioTech), para testar o botão "Enviar projeto".
- **CaraRuim**: professor; vê os projetos dos alunos e pode criar propostas na Turma do Charles.
- **FernandaCosta**: administra a turma Robótica, que não tem alunos nem propostas.
- **EduardaAlves**: não está em nenhuma turma, então mostra as páginas vazias.

> Se mudar os usuários no `seed.sql`, atualize este arquivo também.

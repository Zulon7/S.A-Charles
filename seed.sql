
    -- senha de todos os usuários: 123456 (hash gerado com password_hash)
    INSERT INTO users (id, nome, email, senha, data_criado, ativo, nivel) VALUES
    (1, 'CaraLegal',      'caralegal@example.com',      '$2y$10$WkbIxE2CfLItm33GCIXk2eQ7qP7bsE9WyK2ySjRguTr8tQMJAOY3.', CURRENT_DATE(), 1, 'aluno'),
    (2, 'CaraRuim',       'cararuim@example.com',       '$2y$10$WkbIxE2CfLItm33GCIXk2eQ7qP7bsE9WyK2ySjRguTr8tQMJAOY3.', CURRENT_DATE(), 1, 'professor'),
    (3, 'AnaSouza',       'anasouza@example.com',       '$2y$10$WkbIxE2CfLItm33GCIXk2eQ7qP7bsE9WyK2ySjRguTr8tQMJAOY3.', CURRENT_DATE(), 1, 'aluno'),
    (4, 'BrunoLima',      'brunolima@example.com',      '$2y$10$WkbIxE2CfLItm33GCIXk2eQ7qP7bsE9WyK2ySjRguTr8tQMJAOY3.', CURRENT_DATE(), 1, 'aluno'),
    (5, 'CarlaMendes',    'carlamendes@example.com',    '$2y$10$WkbIxE2CfLItm33GCIXk2eQ7qP7bsE9WyK2ySjRguTr8tQMJAOY3.', CURRENT_DATE(), 1, 'aluno'),
    (6, 'DiegoRocha',     'diegorocha@example.com',     '$2y$10$WkbIxE2CfLItm33GCIXk2eQ7qP7bsE9WyK2ySjRguTr8tQMJAOY3.', CURRENT_DATE(), 1, 'aluno'),
    (7, 'EduardaAlves',   'eduardaalves@example.com',   '$2y$10$WkbIxE2CfLItm33GCIXk2eQ7qP7bsE9WyK2ySjRguTr8tQMJAOY3.', CURRENT_DATE(), 1, 'aluno'),
    (8, 'FernandaCosta',  'fernandacosta@example.com',  '$2y$10$WkbIxE2CfLItm33GCIXk2eQ7qP7bsE9WyK2ySjRguTr8tQMJAOY3.', CURRENT_DATE(), 1, 'professor');

    INSERT INTO turmas (id, nome, data_criado) VALUES
    (1, 'Turma do Charles',                       '2026-02-02'),
    (2, '2º Ano A - Desenvolvimento de Sistemas', '2026-02-10'),
    (3, '3º Ano B - Mecatrônica',                 '2026-03-01'),
    (4, 'Robótica (Contraturno)',                 '2026-08-03');

    -- Alunos em cada turma
    -- EduardaAlves (7) não está em nenhuma turma, para testar a página vazia
    -- A turma 4 ainda não tem alunos
    INSERT INTO user_participa (id_user, id_turma) VALUES
    (1, 1), (3, 1), (4, 1),
    (1, 2), (5, 2), (6, 2),
    (3, 3), (4, 3), (5, 3), (6, 3);

    -- Professores de cada turma
    -- A turma 3 tem dois professores
    INSERT INTO user_modera (id_user, id_turma) VALUES
    (2, 1),
    (8, 2),
    (2, 3), (8, 3),
    (8, 4);

    -- Projetos de cada turma
    -- A turma 4 (Robótica) ainda não tem projetos
    -- O projeto 5 não tem data de entrega, para testar esse caso
    INSERT INTO projetos (id, id_turma, nome, descricao, data_criado, data_entrega) VALUES
    (1, 1, 'Site da Escola',          'Criar um site com as informações da escola usando HTML e CSS.', '2026-02-15', '2026-11-20'),
    (2, 1, 'Calculadora em PHP',      'Calculadora com as quatro operações básicas.',                  '2026-03-10', '2026-04-10'),
    (3, 2, 'Sistema de Biblioteca',   'Cadastro de livros e empréstimos com banco de dados.',          '2026-03-05', '2026-12-01'),
    (4, 3, 'Braço Robótico',          'Montar e programar um braço robótico com Arduino.',             '2026-04-01', '2026-10-30'),
    (5, 3, 'Esteira Automatizada',    NULL,                                                             '2026-09-15', NULL);


    -- senha dos dois usuários: 123456 (hash gerado com password_hash)
    INSERT INTO users (id, nome, email, senha, data_criado, ativo, nivel) VALUES
    (1, 'CaraLegal', 'caralegal@example.com', '$2y$10$WkbIxE2CfLItm33GCIXk2eQ7qP7bsE9WyK2ySjRguTr8tQMJAOY3.', CURRENT_DATE(), 1, 'aluno'),
    (2, 'CaraRuim', 'cararuim@example.com', '$2y$10$WkbIxE2CfLItm33GCIXk2eQ7qP7bsE9WyK2ySjRguTr8tQMJAOY3.', CURRENT_DATE(), 1, 'professor');

    INSERT INTO turmas (id, nome, data_criado) VALUES
    (1, 'Turma do Charles', CURRENT_DATE());

    INSERT INTO user_participa (id_user, id_turma) VALUES
    (1, 1);

    INSERT INTO user_modera (id_user, id_turma) VALUES
    (2, 1);

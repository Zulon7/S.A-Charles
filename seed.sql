
    INSERT INTO users (id, nome, email, senha, data_criado, ativo, nivel) VALUES
    (1, 'CaraLegal', 'caralegal@example.com', '123456', CURRENT_DATE(), 1, 0)
    (2, 'CaraRuim', 'cararuim@example.com', '123456', CURRENT_DATE(), 1, 0);

    
    INSERT INTO user_participa (id_user, id_grupo) VALUES
    (1, 1);

    INSERT INTO user_modera (id_user, id_grupo) VALUES
    (2, 1)

    INSERT INTO grupos (id, nome, data_criado) VALUES
    (1, 'Grupo do Charles', CURRENT_DATE());

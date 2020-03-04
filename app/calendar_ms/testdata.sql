-- Just for some test data. Could be used for error handling testing if time permits.

INSERT INTO 
    events (id, title, start_event, end_event)
VALUES 
    (1, "test1", NOW(), NOW() + INTERVAL 1 DAY);
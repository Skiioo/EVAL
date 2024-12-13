## Tp Noté Symfony
### Merletti Sacha  


Après avoir importé le GitHub sur vscode.

Démarer le serveur Symfony
symfony server:start

Crér la base de données depuis symphony, j'ai utilisé Maria DB qui utilise php myadmin pour des soucis technique, donc la bdd n'est pas disponible sur php myadmin mais elle bien créé.

Nom de la BDD "TP" </br>
php bin/console doctrine:database:create 

Faire les migration.

php bin/console make:migration </br>
php bin/console doctrine:migrations:migrate </br>

Tester l'API sur PostMan:</br>


Requête en json pour créer un utilisateur / s'inscrire : </br>
{ </br>
  "name": "sarha tacha",</br>
  "email": "john.doe@example.com", </br>
  "password": "securepassword", </br>
  "phoneNumber": "+1234567890",</br>
  "roles": "ROLE_USER", </br>
  "relations": "test" </br>
}

Requête pour fair une réservation: </br>

{  </br>
"date": "2024-12-14 10:00:00", </br>
 "timeSlot": "10:00:00", </br>
"eventName": "New Year Party",</br>
 "userId": 3 </br>
 }

ROUTES: </br>

User :  </br>
http://127.0.0.1:8000/user/create Post </br>
http://127.0.0.1:8000/user/{id}    Get </br>
http://127.0.0.1:8000/user/{id}  Delete </br>

réservation :  </br>
http://127.0.0.1:8000/reservation/create  Post </br>
http://127.0.0.1:8000/reservations  Get </br>
http://127.0.0.1:8000/reservation/{id} Delete </br>
http://127.0.0.1:8000/reservation/{id} Get par l’id </br>
http://127.0.0.1:8000/reservations   Get pour afficher toutes les reservation </br>








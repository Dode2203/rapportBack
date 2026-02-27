#Url pour creer utilisateur
  Post http://192.168.88.9:8000/utilisateurs
  #[TokenRequired(['Admin'])]
  Body
    {
      "email":"admin@gmail.com",
      "mdp":"adminadmin",
      "idRole": 1,
      "entite": "Admin"
    }
  Reponse
    {
      "status": "success",
      "data": {
          "email": "admin@gmail.com",
          "mdp": "$2y$10$kXQ/WPe1pV8VniNoumBGguTew7fW36rY4jhUKeZTAaVyYG0bxVqxe",
          "entite": "Admin",
          "role": {
              "name": "Admin",
              "id": 1,
              "createdAt": "2026-02-25T17:17:16+03:00",
              "deletedAt": null,
              "deleted": false
          },
          "id": 1,
          "createdAt": "2026-02-25T17:21:40+03:00",
          "deletedAt": null,
          "deleted": false
      }
  }
#Url pour login
  Post http://192.168.88.9:8000/utilisateurs/login
  Body
    {
      "email":"admin@gmail.com",
      "mdp":"adminadmin"
    }
  Reponse
    {
      "status": "success",
      "data": {
          "membre": {
              "email": "test@gmail.com",
              "role": "Utilisateur",
              "entite": "SP"
          },
          "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ5b3VyLWFwcCIsImF1ZCI6InlvdXItY2xpZW50IiwiaWF0IjoxNzcyMDI5NDk5LjI1NTQ1MSwiZXhwIjoxNzcyMDMzMDk5LjI1NTQ1MSwiaWQiOjIsImVtYWlsIjoidGVzdEBnbWFpbC5jb20iLCJyb2xlIjoiVXRpbGlzYXRldXIiLCJlbnRpdGUiOiJTUCJ9.EuJL-c5gUQ3ZTqldfSnVjDsNN7068DdnYXItYK4a8Cs"
      }
  }

#Url pour creer calendrier
  Post http://192.168.88.9:8000/calendriers
  #[TokenRequired(['Admin'])]
  Body
    {
      "dateDebut": "2026-01-01",
      "dateFin": "2026-01-31",
      "typeCalendrierId": 2
    }
  
#Url pour faire un rapport
  Post http://192.168.88.9:8000/rapports
  Body
    {
      "idCalendrier": 1,
      "activites": [
        {
          "activite": "Reboisement communautaire",
          "effects": [
            {
              "name": "Amélioration de la qualité de l'air"
            },
            {
              "name": "Réduction de l'érosion des sols"
            }
          ],
          "impacts": [
            {
              "name": "Augmentation de la biodiversité"
            },
            {
              "name": "Sensibilisation environnementale de la population"
            }
          ]
        },
        {
          "activite": "Campagne de sensibilisation environnementale",
          "effects": [
            { "name": "Augmentation de la sensibilisation" },
            { "name": "Changement de comportement des citoyens" },
            { "name": "Réduction des déchets plastiques" }
          ],
          "impacts": [
            { "name": "Amélioration de la propreté urbaine" },
            { "name": "Diminution de la pollution" }
          ]
        }
      ]
    }

    Reponse
      {
          "status": "success",
          "data": [
              {
                  "id": 17,
                  "createdAt": "2026-02-25 19:13:08",
                  "deletedAt": null,
                  "utilisateur": {
                      "email": "admin@gmail.com",
                      "mdp": "$2y$10$r43kiahjMyCje0hyQ5KWjOLNRQil5KmArQqwy1qKpXP6FMSJ48m6e",
                      "entite": "Admin",
                      "id": 1,
                      "createdAt": "2026-02-25 18:19:39",
                      "deletedAt": null,
                      "role": "Admin"
                  },
                  "calendrier": {
                      "id": 1,
                      "dateDebut": "2026-01-01",
                      "dateFin": "2026-01-31",
                      "typeCalendriers": {
                          "id": 1,
                          "name": "Hebdomadaire"
                      }
                  },
                  "activites": [
                      {
                          "activite": {
                              "name": "Projet ERP",
                              "id": 17,
                              "createdAt": "2026-02-25 19:13:08",
                              "deletedAt": null
                          },
                          "effectsImpacts": [
                              {
                                  "effect": "Retard livraison",
                                  "impact": "Décalage planning",
                                  "id": 1,
                                  "createdAt": "2026-02-25 19:13:08",
                                  "deletedAt": null
                              },
                              {
                                  "effect": "Absence équipe",
                                  "impact": "Baisse productivité",
                                  "id": 2,
                                  "createdAt": "2026-02-25 19:13:08",
                                  "deletedAt": null
                              }
                          ]
                      },
                      {
                          "activite": {
                              "name": "Projet CRM",
                              "id": 18,
                              "createdAt": "2026-02-25 19:13:08",
                              "deletedAt": null
                          },
                          "effectsImpacts": [
                              {
                                  "effect": "Retard test",
                                  "impact": "Vody bobota",
                                  "id": 3,
                                  "createdAt": "2026-02-25 19:13:08",
                                  "deletedAt": null
                              }
                          ]
                      }
                  ]
              }
          ]
      }
#Pour avoir les listes des calendriers  
  GET http://192.168.88.9:8000/calendriers      
  #[TokenRequired(admin)]
  methode get
    response
      {
            "status": "success",
            "data": [
                {
                    "dateDebut": "2026-01-01",
                    "dateFin": "2026-01-31",
                    "id": 1,
                    "typeCalendrier": {
                        "name": "Hebdomadaire",
                        "id": 1
                    }
                }
            ]
        }

#url pour get rapport
  Get http://192.168.88.9:8000/rapports
  #[TokenRequired]
  Get http://192.168.88.9:8000/rapports/calendrier?idCalendrier=1
  #[TokenRequired(admin)]
  Reponse
    {
      "status": "success",
      "data": [
          {
              "id": 17,
              "utilisateur": {
                  "role": "Admin"
              },
              "calendrier": {
                  "dateDebut": "2026-01-01",
                  "dateFin": "2026-01-31",
                  "typeCalendrier": {
                      "name": "Hebdomadaire"
                  }
              },
              "activites": [
                  {
                      "activite": {
                          "name": "Projet ERP",
                          "id": 17
                      },
                      "effectsImpacts": [
                          {
                              "effect": "Retard livraison",
                              "impact": "Décalage planning",
                              "id": 1
                          },
                          {
                              "effect": "Absence équipe",
                              "impact": "Baisse productivité",
                              "id": 2
                          }
                      ]
                  },
                  {
                      "activite": {
                          "name": "Projet CRM",
                          "id": 18
                      },
                      "effectsImpacts": [
                          {
                              "effect": "Retard test",
                              "impact": "Vody bobota",
                              "id": 3
                          }
                      ]
                  }
              ]
          }
      ]
  }
    
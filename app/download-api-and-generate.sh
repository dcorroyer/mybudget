#!/bin/sh

# Télécharger le fichier de documentation API
curl -s http://mybudget.web.localhost/api/doc.json > ./assets/api/api-doc.json

# Vérifier si le téléchargement a réussi
if [ $? -ne 0 ]; then
  echo "Erreur lors du téléchargement du fichier de documentation API"
  exit 1
fi

# Créer le dossier des modèles s'il n'existe pas
mkdir -p ./assets/api/models
mkdir -p ./assets/api/generated

# Exécuter Orval
orval --config orval.config.ts

echo "Génération de l'API terminée avec succès" 
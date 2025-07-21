# AMELIORATIONS

## 📊 1. Softdelete on account to keep transactions in the board

## 🛠️ 2. Orval vs openapi-ts

### Recommandation : **Conserver Orval** ✅

#### Analyse comparative

| Critère | Orval (actuel) | openapi-ts |
|---------|----------------|------------|
| Génération hooks React Query | ✅ Automatique | ❌ Manuel |
| Organisation par tags | ✅ tags-split | ❌ Un seul fichier |
| Intégration axios | ✅ Native | ❌ À implémenter |
| Mocks automatiques | ✅ MSW + Faker | ❌ Non |
| Popularité | 346k/semaine | 1.4M/semaine |
| Taille du code généré | ❌ Important | ✅ Minimal |

### Points d'amélioration possibles
- Optimiser le script `download-api-and-generate.sh`
- Ajouter un cache pour éviter les régénérations inutiles
- Documenter les patterns d'utilisation dans l'équipe

---

## 🎨 3. Refonte du frontend React

### Objectifs
- Améliorer l'UX/UI basé sur le POC réussi
- Moderniser l'architecture React
- Implémenter les patterns appris récemment

### Approche recommandée

1. **Phase de préparation** :
   - Documenter le design system du POC
   - Identifier les composants réutilisables
   - Planifier la migration progressive

2. **Architecture moderne** :
   - Server Components (si Next.js)
   - Suspense boundaries
   - Error boundaries systématiques
   - Optimistic updates avec React Query

3. **Migration progressive** :
   - Commencer par les pages isolées
   - Migrer les composants partagés
   - Conserver l'API existante

### Technologies à considérer
- **UI** : Conserver Mantine ou migrer vers Radix UI + Tailwind
- **State** : Zustand pour le state client (si nécessaire)
- **Forms** : React Hook Form + Zod (déjà en place)
- **Charts** : Recharts (déjà utilisé)

---

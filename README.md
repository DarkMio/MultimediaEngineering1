# MultimediaEngineering1
Chaotic code for a useful webservice.

### Deployment:

1. Upload Content to Website
2. Access database and create a Schema ``tattooliste``
3. Use SQL import to get a basic configuration of the website
4. Change in api/v1/api.class.php on Line 84 the credentials
(https://github.com/DarkMio/MultimediaEngineering1/blob/master/api/v1/API.class.php#L84)
5. Use services, configure cloud caching, etc.

### Current short comings:
- No social media (no website, facebook, etc)
- Studio rating is broken
- No ability to search through all studios
- No picture ability
- Broken interaction when adding a studio that is interpreted multiple times
- Google Maps Marker are accurate but without any labelling
- Code consistency in JS is inconsistent
- No adspaces in layout
- No studio name search (searching by name, rather than adress)
- No single page studio display ^(but that's somewhere in the pipeline)

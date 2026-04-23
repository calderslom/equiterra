FROM php:8.1-apache

# Enable the mysqli extension (needed for all DB calls)
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Copy application source into Apache's web root
# This is overridden by the volume mount in the YAML - this serves as a fallback for future deployments
COPY ./app /var/www/html/

# Make uploaded images directory set write access only for the owner
RUN mkdir -p /var/www/html/uploads && chmod 755 /var/www/html/uploads

# Expose port 80 (internally) for Apache; port 8080 is outward facing port (refer to YAML)
EXPOSE 80

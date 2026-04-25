FROM php:8.1-apache

# Enable the mysqli extension (needed for all DB calls)
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Copy application source files into Apache's web root directory
# This is overridden by the volume mount in the YAML - this serves as a fallback for future deployments
COPY ./app /var/www/html/

# Make uploaded user data directory and set write access only for the owner
RUN mkdir -p /var/www/html/uploads && chmod 755 /var/www/html/uploads

# Expose port 80 (internally) for Apache; port 8080 is outward facing port (refer to YAML)
EXPOSE 80

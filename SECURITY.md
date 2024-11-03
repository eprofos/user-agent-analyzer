# Security Policy

## Reporting a Vulnerability

The EprofosUserAgentAnalyzerBundle team takes security bugs seriously. We appreciate your efforts to responsibly disclose your findings and will make every effort to acknowledge your contributions.

To report a security issue, please use the GitHub Security Advisory ["Report a Vulnerability"](https://github.com/eprofos/user-agent-analyzer/security/advisories/new) tab.

The team will send a response indicating the next steps in handling your report. After the initial reply to your report, the security team will keep you informed of the progress towards a fix and full announcement, and may ask for additional information or guidance.

## Supported Versions

We release patches for security vulnerabilities for the following versions:

| Version | Supported          |
| ------- | ------------------ |
| 2.x     | :white_check_mark: |
| 1.x     | :x:               |

## Security Update Policy

Security updates will be released as follows:

- For critical vulnerabilities, we will release a security patch as soon as possible.
- For medium and low severity vulnerabilities, we will include the fix in the next regular release.

## Best Practices

When using this bundle, follow these security best practices:

1. Always keep your dependencies up to date:
   ```bash
   composer update eprofos/user-agent-analyzer --with-dependencies
   ```

2. Regularly check for security advisories:
   ```bash
   composer audit
   ```

3. Use HTTPS for all API endpoints that utilize this bundle.

4. Implement proper input validation and sanitization before passing user agent strings to the analyzer.

5. Follow the principle of least privilege when configuring access to user agent analysis features.

6. Enable logging to monitor for potential security issues:
   ```yaml
   # config/packages/monolog.yaml
   monolog:
       handlers:
           security:
               level: info
               type: stream
               path: "%kernel.logs_dir%/security.log"
   ```

## Security Features

The bundle includes several security measures:

- Input validation and sanitization of user agent strings
- Protection against common attack vectors
- Comprehensive logging support for security monitoring
- Safe handling of malformed user agent strings

## Contact

For any security-related questions, please contact:

- Security Team: contact@eprofos.com
- Lead Maintainer: Houssem TAYECH (houssem@eprofos.com)

## Attribution

We appreciate the security research community's efforts in keeping our users secure. Responsible disclosure of vulnerabilities helps us ensure the security and privacy of our users.

## License

This security policy and all security-related features are covered under the [MIT License](LICENSE).

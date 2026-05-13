describe('Forgot Password Page', function() {
    it('fails with invalid inputs', function() {
        cy.visit('/password/reset')

        cy.location('pathname').should('eq', '/password/reset')

        const user = {
            email: 'test00000@example.com',
            username: 'test00000'
        }

        cy.get('#email').type(user.email)
        cy.get('#username').type(user.username)

        cy.get('button').click()

        cy.location('pathname').should('eq', '/password/reset')

        cy.get('#email').siblings('span.invalid-feedback').should('exist')
        cy.get('#email').siblings('span.invalid-feedback').contains("We can't find a user with that e-mail address.")
    });

    it('succeeds with valid inputs', function() {
        cy.visit('/password/reset')

        cy.location('pathname').should('eq', '/password/reset')

        const user = {
            email: 'test00001@example.com',
            username: 'test00001'
        }

        cy.get('#email').type(user.email)
        cy.get('#username').type(user.username)

        cy.get('button').click()

        cy.location('pathname').should('eq', '/password/reset')

        cy.get('#email').siblings('span.invalid-feedback').should('not.exist')

        cy.get('div.alert.alert-success').contains('We have e-mailed your password reset link!')

        cy.get('#email').invoke('text').should('eq', '')
        cy.get('#username').invoke('text').should('eq', '')
    });
})

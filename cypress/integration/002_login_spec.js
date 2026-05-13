describe('Login Page', function() {
    it('failed in authentication', function() {
        cy.visit('/login')

        cy.location('pathname').should('eq', '/login')

        const username = 'username'
        const password = 'password'
        const errorMessage = 'These credentials do not match our records.'

        cy.get('#username').type(username)
        cy.get('#password').type(password)

        cy.get('#username').invoke('val').should('eq', username)
        cy.get('#password').invoke('val').should('eq', password)

        cy.get('button').click()

        cy.location('pathname').should('eq', '/login')

        cy.get('.invalid-feedback').should('exist')
        cy.get('.invalid-feedback').contains(errorMessage)
    })

    it('succeeded in authentication', function() {
        cy.visit('/login')

        cy.location('pathname').should('eq', '/login')

        const username = 'test00001'
        const password = 'test123456'

        cy.get('#username').type(username)
        cy.get('#password').type(password)

        cy.get('#username').invoke('val').should('eq', username)
        cy.get('#password').invoke('val').should('eq', password)

        cy.get('button').click()

        cy.get('.invalid-feedback').should('not.exist')

        cy.location('pathname').should('eq', '/dashboard')

        cy.get('#navbarDropdown').contains('Welcome')
    })

    it('multitple failed in authentication', function() {
        cy.visit('/login')

        cy.location('pathname').should('eq', '/login')

        const username = 'username'
        const password = 'password'
        const errorMessage = 'Too many login attempts.'

        cy.get('#username').type(username)
        cy.get('#password').type(password)

        cy.get('button').click()
        cy.location('pathname').should('eq', '/login')

        cy.get('#password').type(password)
        cy.get('button').click()
        cy.location('pathname').should('eq', '/login')

        cy.get('#password').type(password)
        cy.get('button').click()
        cy.location('pathname').should('eq', '/login')

        cy.get('#password').type(password)
        cy.get('button').click()
        cy.location('pathname').should('eq', '/login')

        cy.get('#password').type(password)
        cy.get('button').click()
        cy.location('pathname').should('eq', '/login')

        cy.get('.invalid-feedback').should('exist')
        cy.get('.invalid-feedback').contains(errorMessage)
    })
})

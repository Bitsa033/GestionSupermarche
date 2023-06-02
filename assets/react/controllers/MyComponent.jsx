// assets/react/controllers/MyComponent.jsx
import React from 'react';

export default function (props) {
    return <div>
        <h1 className='inline'>Bienvenue {props.fullName}</h1>
        <h3 className='inline'> dans ton application symfony/UX</h3>
        <div className="row">
            <div className="col-md-4">

                <form action="" method="post" autoComplete='off'>

                    <div className="form-group">
                        <input placeholder='Votre nom' type="text" name="nom" id="nom" className='form-group' />
                    </div>

                    <div className="form-group">
                        <input placeholder='Votre age' type="number" name="age" id="age" className='form-group' />
                    </div>

                    <div className="form-group">
                        <input placeholder='Votre sexe' type="text" name="sexe" id="sexe" className='form-group' />
                    </div>

                    <div className="form-group">
                        <input placeholder='Votre email' type="email" name="email" id="email" className='form-group' />
                    </div>

                    <div className="form-group">
                        <input placeholder='Votre contact' type="tel" name="tel" id="tel" className='form-group' />
                    </div>

                    <div className="form-group">
                        <input type="submit" name="email" id="submit" className='form-group btn btn-success' />
                    </div>

                </form>
            </div>
            {/* fincol */}
            <div className="col-md-8">
                <table className="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Age</th>
                            <th>Sexe</th>
                            <th>Email</th>
                            <th>Contact</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Alain</td>
                            <td>8 Ans</td>
                            <td>Homme</td>
                            <td>alain@gmail.com</td>
                            <td>655 10 26 42</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        {/* finrow */}
    </div>;
}
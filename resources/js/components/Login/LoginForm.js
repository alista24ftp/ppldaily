import React, {useState, useEffect} from 'react';

const LoginForm = props => {
    const [errorMsg, setErrorMsg] = useState('');
    const [formSubmitting, setFormSubmitting] = useState(false);
    const [user, setUser] = useState({
        username: '',
        password: '',
    });

    const handleUserName = (e) => {
        let value = e.target.value;
        setUser(prevState => ({
            ...prevState,
            username: value
        }));
    }

    const handlePassword = (e) => {
        let value = e.target.value;
        setUser(prevState => ({
            ...prevState,
            password: value
        }));
    }

    const handleSubmit = async (e) => {
        e.preventDefault();
        setFormSubmitting(true);
        try{
            let response = await axios.post(`/api/v1/auth/login`, user);
            console.log(response);
            if(response.status == 200){
                setFormSubmitting(false);
                props.successCallback();
            }
        }catch(err){
            console.error(err);
        }finally{
            setFormSubmitting(false);
        }
    }

    return (
        <form onSubmit={handleSubmit}>
            <div className="form-group">
                <label htmlFor="username">Username</label>
                <input type="text" id="username" name="username" className="form-control"
                    onChange={handleUserName} required />
            </div>
            <div className="form-group">
                <label htmlFor="password">Password</label>
                <input type="password" id="password" name="password" className="form-control"
                    onChange={handlePassword} required />
            </div>
            <button type="submit" className="btn btn-primary" disabled={formSubmitting}>
                {formSubmitting ? 'Logging in...' : 'Log In'}
            </button>
        </form>
    );
}

export default LoginForm;

import React, {useState, useEffect} from 'react';

const RegisterForm = props => {
    const [formSubmitting, setFormSubmitting] = useState(false);
    const [captchaData, setCaptchaData] = useState({
        captchaKey: null,
        captchaContent: null
    });
    const [user, setUser] = useState({
        username: '',
        email: '',
        password: '',
        password_confirmation: '',
        captcha: ''
    });

    useEffect(() => {
        async function generateCaptcha(){
            await handleCaptchaGenerate();
        }
        generateCaptcha();
    }, []);

    const handleUserName = (e) => {
        let value = e.target.value;
        setUser(prevState => ({
            ...prevState,
            username: value
        }));
    }

    const handleEmail = (e) => {
        let value = e.target.value;
        setUser(prevState => ({
            ...prevState,
            email: value
        }));
    }

    const handlePassword = (e) => {
        let value = e.target.value;
        setUser(prevState => ({
            ...prevState,
            password: value
        }));
    }

    const handlePasswordConfirmation = (e) => {
        let value = e.target.value;
        setUser(prevState => ({
            ...prevState,
            password_confirmation: value
        }));
    }

    const handleCaptchaInput = (e) => {
        let value = e.target.value;
        setUser(prevState => ({
            ...prevState,
            captcha: value
        }));
    }

    const handleCaptchaGenerate = async () => {
        try{
            let response = await axios.post(`/api/v1/captcha`);
            if(response.status == 201){
                setCaptchaData({
                    captchaContent: response.data.captcha_content,
                    captchaKey: response.data.captcha_key
                });
            }else{
                console.error('ERROR: Cannot generate captcha');
            }
        }catch(err){
            console.error(err);
        }
    }

    const handleSubmit = async (e) => {
        e.preventDefault();
        let userData = {
            ...user,
            captcha_key: captchaData.captchaKey
        };
        setFormSubmitting(true);
        try{
            let response = await axios.post(`/api/v1/auth/register`, userData);
            if(response.status == 201){
                console.log(response);
                props.successCallback();
            }
        }catch(err){
            console.error(err);
            alert('ERROR: User registration failed');
        }finally{
            setFormSubmitting(false);
        }
    }

    return (
        <form onSubmit={handleSubmit}>
            <div className="form-group">
                <label htmlFor="username">Username</label>
                <input id="username" name="username" className="form-control" type="text"
                    onChange={handleUserName} value={user.username} required />
            </div>
            <div className="form-group">
                <label htmlFor="email">Email</label>
                <input id="email" name="email" className="form-control" type="text"
                    onChange={handleEmail} value={user.email} required />
            </div>
            <div className="form-group">
                <label htmlFor="password">Password</label>
                <input id="password" type="password" name="password" className="form-control"
                    onChange={handlePassword} value={user.password} required />
            </div>
            <div className="form-group">
                <label htmlFor="password_confirmation">Confirm Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation"
                    className="form-control" value={user.password_confirmation}
                    onChange={handlePasswordConfirmation} required />
            </div>
            <div className="form-group row">
                <label htmlFor="captcha" className="col-4 col-form-label text-right">Captcha Code</label>
                <div className="col-6">
                    <input id="captcha" name="captcha" type="text" className="form-control"
                        onChange={handleCaptchaInput} value={user.captcha} required />
                    <img id={captchaData.captchaKey} src={captchaData.captchaContent}
                        onClick={handleCaptchaGenerate}
                        className="thumbnail captcha mt-3 mb-2" title="Click to generate captcha" />
                </div>
            </div>
            <button type="submit" className="btn btn-primary">
                {formSubmitting ? 'Registering...' : 'Register'}
            </button>
        </form>
    );
}

export default RegisterForm;

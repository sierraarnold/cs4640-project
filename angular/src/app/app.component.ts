import { Component } from '@angular/core';
import { Conversion } from './conversion';

import { HttpClient, HttpErrorResponse, HttpParams } from '@angular/common/http';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})
export class AppComponent {

  // dependency injection
  constructor(private http: HttpClient) {  }

  title = 'Submit a New Conversion';
  author = 'Sierra Arnold and Min Suk Kim';

  confirm_msg = '';
  display_confirm = false;
  data_submitted = '';

  /* create an instance of a conversion, assuming there is one existent */
  conversionModel = new Conversion('', '', null);
  responsedata = new Conversion('','',null);    // to store a response from the backend

  confirmSubmission(data: any): void {
     console.log(data);
     this.confirm_msg = 'You submitted the conversion ' + data.unit1 + ' to ' + data.unit2 + '. Thank you!';
     this.display_confirm = true;
  }

  // passing in a form variable of type any, no return result
  onSubmit(form: any): void {
     console.log('You submitted value: ', form);
     this.data_submitted = form;

     console.log('form submitted ', form);

     /*------*/
     // Prepare to send a request to the backend PHP
     let params = JSON.stringify(form);

     // To send a POST request, pass data as an object.

     this.http.post<Conversion>('http://localhost:8080/cs4640-project/search/addConversion.php', params)
     .subscribe((response_from_php) => {
        // Receive a response successfully, do something here
        this.responsedata = response_from_php;
     }, (error_in_comm) => {
        // An error occurs, handle an error in some way.
        console.log('Error ', error_in_comm);
     });
  }
}


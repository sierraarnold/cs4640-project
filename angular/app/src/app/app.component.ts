import { Component } from '@angular/core';
import { Conversion } from './conversion';

import { HttpClient, HttpErrorResponse, HttpParams } from '@angular/common/http';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})
export class AppComponent {
  constructor(private http: HttpClient) {  }

  title = 'Submit a New Conversion';
  author = 'Sierra Arnold and Min Suk Kim';

  confirm_msg = '';
  data_submitted = '';


  /* create an instance of an Conversion */
  /* we will bind conversionModel to the form, allowing an update / delete transaction */
  conversionModel = new Conversion('', '', null);

  confirmSubmission(data: any): void {
     console.log(data);
     this.confirm_msg = 'Thank you for submitting a conversion!';
  }

  responsedata = new Conversion('','',null);    // to store a response from the backend

  // passing in a form variable of type any, no return result
  onSubmit(form: any): void {
     console.log('You submitted value: ', form);
     this.data_submitted = form;

     console.log('form submitted ', form);

     /*------*/
     // Prepare to send a request to the backend PHP
     // 1. Convert the form data to JSON format
     let params = JSON.stringify(form);

     // 2. Send an HTTP request to a backend
     // To send a POST request, pass data as an object.
     // The HttpClient.post method returns an observable<Conversion>, then we subscribe to this observable

     this.http.post<Conversion>('http://localhost/cs4640-project/ng-php/ng-post.php', params)
     .subscribe((response_from_php) => {
        // Receive a response successfully, do something here

        // Suppose we just want to assign a response from a PHP backend
        // to a responsedata property of this controller,
        // so that we can use it (or bind it) to display on screen

        this.responsedata = response_from_php;

        // The subcribe above means that this observable takes response_from_php
        // being emitted and set it to this.responsedata

     }, (error_in_communication) => {
        // An error occurs, handle an error in some way.
        console.log('Error ', error_in_communication);
     })
  }
}

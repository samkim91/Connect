package com.example.chinacompetition.chat;

import android.app.AlertDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.RatingBar;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;

import com.bumptech.glide.Glide;
import com.example.chinacompetition.CommonValue;
import com.example.chinacompetition.MyToken;
import com.example.chinacompetition.R;
import com.example.chinacompetition.RetrofitService;
import com.example.chinacompetition.contract.ContractActivity;
import com.example.chinacompetition.contract.ContractDetailActivity;


import org.json.JSONArray;

import java.io.IOException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;

import okhttp3.Interceptor;
import okhttp3.OkHttpClient;
import okhttp3.Request;
import okhttp3.ResponseBody;
import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;
import retrofit2.Retrofit;

public class MessageAdapter extends RecyclerView.Adapter<MessageAdapter.MessageViewHolder> {

    /**  Message list Adapter  ***/

    private final ArrayList<MessageList> messageArrayList ;
    private MessageClickListener messageClickListener;
    private Context context;
    
    private String TAG = "MessageAdapter";

    public MessageAdapter(ArrayList<MessageList> messageArrayList, Context context) {
        this.messageArrayList = messageArrayList;
        this.context = context;
    }

    public class MessageViewHolder extends RecyclerView.ViewHolder {

        protected TextView id ;
        protected TextView name ;
        protected Button contract;
        protected Button contractDetail;
        protected Button leaveReview;


        public MessageViewHolder(@NonNull View itemView) {
            super(itemView);

            this.id =  itemView.findViewById(R.id.text_item_list_message_id);
            this.name =  itemView.findViewById(R.id.text_item_list_message_name);
            this.contract =  itemView.findViewById(R.id.btn_item_list_message_contract);
            this.contractDetail =  itemView.findViewById(R.id.btn_item_list_message_contract_detail);


            this.leaveReview = itemView.findViewById(R.id.btn_item_list_message_leave_review);


        }


    }


    @NonNull
    @Override
    public MessageViewHolder onCreateViewHolder(@NonNull ViewGroup viewGroup, int viewType) {



        View view = LayoutInflater.from(viewGroup.getContext())
                .inflate(R.layout.item_list_message, viewGroup, false);


        MessageViewHolder messageViewHolder = new MessageViewHolder(view);


        return messageViewHolder;
    }



    @Override
    public void onBindViewHolder(@NonNull MessageViewHolder messageViewHolder, final int position) {
        messageViewHolder.id.setText(messageArrayList.get(position).getId());
        messageViewHolder.name.setText(messageArrayList.get(position).getName());
        messageViewHolder.contract.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(view.getContext(), ContractActivity.class);
                intent.putExtra("messageId", messageArrayList.get(position).getId());
                intent.putExtra("messageName", messageArrayList.get(position).getName());
                intent.putExtra("loginedId", CommonValue.getId());
                view.getContext().startActivity(intent);
            }
        });
        messageViewHolder.contractDetail.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(view.getContext(), ContractDetailActivity.class);
                intent.putExtra("messageId", messageArrayList.get(position).getId());
                intent.putExtra("messageName", messageArrayList.get(position).getName());
                intent.putExtra("loginedId", CommonValue.getId());
                view.getContext().startActivity(intent);
            }
        });
        messageViewHolder.leaveReview.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                AlertDialog.Builder builder = new AlertDialog.Builder(context);
                View dialogView = LayoutInflater.from(context).inflate(R.layout.dialog_write_review, null);

                final EditText dialogEditText = dialogView.findViewById(R.id.dialogEditText);
                final RatingBar dialogRatingBar = dialogView.findViewById(R.id.dialogRatingBar);

                builder.setView(dialogView)
                        .setPositiveButton("send", new DialogInterface.OnClickListener() {
                            @Override
                            public void onClick(DialogInterface dialog, int which) {
//                                String identi = messageArrayList.get(position);

                                String targetId = messageArrayList.get(position).getId();
                                String writeId = CommonValue.getId();
                                String content = dialogEditText.getText().toString();
                                String numStars = String.valueOf(dialogRatingBar.getRating());

                                SimpleDateFormat simpleDateFormat = new SimpleDateFormat("dd-MM-yyyy-hh-mm-ss");
                                String time = simpleDateFormat.format(new Date());
                                String identi = targetId+writeId+time;

                                Log.e(TAG, "Review!! : "+identi+"/"+numStars+"/"+content);
                                MakeReview(identi, numStars, content, targetId, writeId, time);

                            }
                        })
                        .setNegativeButton("cancel", new DialogInterface.OnClickListener() {
                            @Override
                            public void onClick(DialogInterface dialog, int which) {

                            }
                        }).create().show();
            }
        });


        // 클릭이벤트
        if(messageClickListener != null){
            final int pos = position;
            messageViewHolder.itemView.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    messageClickListener.onItemClicked(pos);
                }
            });
        }


    }

    @Override
    public int getItemCount() {
        return messageArrayList.size();
    }


    public interface MessageClickListener {

        // 클릭이벤트
        void onItemClicked(int position);
    }

    public void setMessageClickListener(MessageClickListener messageClickListener) {
        this.messageClickListener = messageClickListener;
    }

    
    
    //make MakeReview
    private void MakeReview(String identi, String title, String content, String targetid, String writid, String day){
        Log.d(TAG, "makeContract");

        // peers
        JSONArray peer_jsonArray = new JSONArray();
        peer_jsonArray.put("peer0.org1.example.com");
        peer_jsonArray.put("peer0.org2.example.com");

        //args
        JSONArray args_jsonArray = new JSONArray();
        args_jsonArray.put(identi);
        args_jsonArray.put(title);
        args_jsonArray.put(content);
        args_jsonArray.put(targetid);
        args_jsonArray.put(writid);
        args_jsonArray.put(day);

        // 헤더에 토큰을 넣으려고 레트로핏을 네트워크 층에서 가로챈 다음에 수정해줘야함.
        // okhttp 클라이언트 빌더를 선언해주고 인터셉트 함수 안에서 필요한 헤더를 추가한다. 메소드나 바디는 기존 요청의 것을 사용한다.
        OkHttpClient.Builder httpClient = new OkHttpClient.Builder();
        httpClient.addInterceptor(new Interceptor() {
            @Override
            public okhttp3.Response intercept(Chain chain) throws IOException {

                Request original = chain.request();

                Request request = original.newBuilder()
                        .header("authorization", "Bearer "+ MyToken.getmToken())
                        .header("content-type", "application/json")
                        .method(original.method(), original.body())
                        .build();

                return chain.proceed(request);
            }
        });

        OkHttpClient client = httpClient.build();

        Retrofit retrofit = new Retrofit.Builder()
                .baseUrl(MyToken.getBcURL())
                .client(client)
                .build();

        RetrofitService retrofitService = retrofit.create(RetrofitService.class);
        Call<ResponseBody> call = retrofitService.exeChaincode("MakeReview", peer_jsonArray.toString(), args_jsonArray.toString());

        call.enqueue(new Callback<ResponseBody>() {
            @Override
            public void onResponse(Call<ResponseBody> call, Response<ResponseBody> response) {
                if(response!=null){
                    Log.d(TAG, "onResponse: "+response);
                }else {
                    Log.d(TAG, "response null");
                }
                try {
                    // 서버에서 보낸 값을 스트링에 담음.
                    String result = response.body().string();
                    if(response.isSuccessful()){
                        Log.d(TAG, "result is successful: "+result);
                        Toast.makeText(context, "송금완료.", Toast.LENGTH_SHORT).show();

                    }else{
                        Log.d(TAG, "unsuccessful: "+result);
                        Toast.makeText(context, "서버와 통신이 좋지 않아요.", Toast.LENGTH_SHORT).show();
                    }

                } catch (IOException e) {
                    e.printStackTrace();
                }

            }

            @Override
            public void onFailure(Call<ResponseBody> call, Throwable t) {
                Log.d(TAG, "onFailure: "+t);
            }
        });
    }//
}

